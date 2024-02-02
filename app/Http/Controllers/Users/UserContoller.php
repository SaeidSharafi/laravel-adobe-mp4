<?php

namespace App\Http\Controllers\Users;

use App\DataTable\InertiaTableExtended;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Auth\User;
use App\Services\AlertResponseService;
use App\Services\ModelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Response;
use Inertia\ResponseFactory;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        Session::put('filters.user', request()->all());

        $users = QueryBuilder::for(User::class)
            ->defaultSort('first_name', 'last_name')
            ->allowedSorts(['first_name', 'last_name', 'email'])
            ->allowedFilters(['first_name', 'last_name', 'email','phone', AllowedFilter::trashed(),AllowedFilter::exact('role', 'roles.id')])
            ->with('roles', fn ($query) => $query->select('id', 'name'))
            ->paginate(request('perPage'))
            ->withQueryString();

        return inertia('Users/Index', [
            'users' => $users,
        ])->exTable(function (InertiaTableExtended $table) {
            $table->selectFilter(
                key: 'trashed',
                options: ['with' => __('general.all'), 'only' => __('general.trashed')],
                label: __('general.view'),
                noFilterOptionLabel: __('general.not_deleted')
            );
            $table->selectFilter(
                key: 'role',
                options: Role::select(['id','label'])->get()->pluck('label', 'id')->toArray(),
                label: __('auth.role.title.self'),
                noFilterOption: false
            );
            $table->searchInput('first_name', __('auth.user.first_name'))
                ->searchInput('last_name', __('auth.user.last_name'))
                ->searchInput('email', __('auth.user.email'))
                ->searchInput('phone', __('auth.user.phone'));
            $table
                ->column(label: 'Actions', translation: __('general.action'))
                ->column('first_name', translation: __('auth.user.first_name'))
                ->column('last_name', translation: __('auth.user.last_name'))
                ->column('email', translation: __('auth.user.email'))
                ->column('phone', translation: __('auth.user.phone'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|ResponseFactory
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $roles = Role::query()
            ->select('name as value', 'label')
            ->when( ! auth()->user()->is_admin, function ($roles) {
                $can_override = auth()->user()?->getAllowedOverrides();
                return $roles->where('system', false)->whereIn('name', $can_override);
            })
            ->get();

        return inertia('Users/Create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        $data['email_verified_at '] = Carbon::now();
        $data['password']           = Hash::make($data['password']);

        $data['roles'][] = ['user'];

        DB::transaction(
            function () use ($data) {
                $user = User::create($data);
                $user->assignRole($data['roles']);
            }
        );

        return redirect()->route('users.user.index', Session::get('filters.user'))
            ->withStatus(AlertResponseService::createSuccess('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return Response|ResponseFactory
     */
    public function show(User $user)
    {
        $this->authorize('create', $user);
        $user_resource = UserResource::make($user);

        return inertia('Users/Edit', ['user' => $user_resource]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return Response|ResponseFactory
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        if ($user->is_admin && auth()->user()?->id !== $user->id) {
            abort(403);
        }

        $user_resource = UserResource::make($user);

        $roles = Role::query()
            ->select('name as value', 'label')
            ->when( ! auth()->user()->is_admin, function ($roles) {
                $can_override = auth()->user()?->getAllowedOverrides();
                return $roles->where('system', false)->whereIn('name', $can_override);
            })
            ->get();

        return inertia('Users/Edit', ['user' => $user_resource, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        if ($user->is_admin && auth()->user()?->id !== $user->id) {
            abort(403);
        }



        if (array_key_exists('password', $data)) {
            if ( ! $data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }
        }

        $data['roles'][] = ['user'];
        DB::transaction(
            function () use ($data, $user) {
                $user->update($data);
                $user->syncRoles($data['roles']);
            }
        );

        return redirect()->route('users.user.index', Session::get('filters.user'))
            ->withStatus(AlertResponseService::updateSuccess('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return ModelService::softDelete($user, User::CONSTRAINS, 'user');
    }

    /**
     * Rstore the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function restore(User $user)
    {
        $this->authorize('restore', $user);
        $user->restore();
        return redirect()->back()
            ->withStatus(
                AlertResponseService::success(__('response.user.restore.success'))
            );
    }
}
