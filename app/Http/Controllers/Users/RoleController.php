<?php

namespace App\Http\Controllers\Users;

use App\DataTable\InertiaTableExtended;
use App\Exceptions\RelationshipException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRoleRequest;
use App\Http\Requests\Users\UpdateRoleRequest;
use App\Services\AlertResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\ResponseFactory;
use Momentum\Modal\Modal;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(): \Inertia\Response|ResponseFactory
    {
        $this->authorize('viewAny', Role::class);

        $roles = QueryBuilder::for(Role::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'label'])
            ->allowedFilters(['name', 'label'])
            ->paginate(request('perPage'))
            ->withQueryString();

        return inertia('Roles/Index', [
            'roles' => $roles,
        ])->exTable(function (InertiaTableExtended $table) {
            $table->searchInput('name', __('auth.role.name'));
            $table->searchInput('label', __('auth.role.label'));
            $table->column('name', translation: __('auth.role.name'))
                ->column(label: 'Actions', translation: __('general.action'))
                ->column('label', translation: __('auth.role.label'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(): Modal
    {
        $this->authorize('create', Role::class);

        $roles = Role::query()
            ->select('name as value', 'label')
            ->when( ! auth()->user()->is_admin, function ($roles) {
                $can_override = auth()->user()?->getAllowedOverrides();
                return $roles->whereIn('name', $can_override);
            })
            ->get()
            ->toArray();

        return Inertia::modal('Roles/Create', compact('roles'))
            ->baseRoute('users.role.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $data = $request->validated();

        $data['guard']         = 'web';
        $data['allowoverride'] = collect($data['allowoverride'])->join(",");

        $role = Role::create($data);

        return redirect()->route('users.role.index')
            ->withStatus(AlertResponseService::createSuccess('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function show($id): Response
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Role $role): Modal
    {
        $this->authorize('update', $role);

        $roles = Role::query()
            ->select('name as value', 'label')
            ->when( ! auth()->user()->is_admin, function ($roles) {
                $can_override = auth()->user()?->getAllowedOverrides();
                return $roles->whereIn('name', $can_override);
            })
            ->get()
            ->pluck('label', 'value')
            ->toArray();

        $role->allowoverride = explode(',', $role->allowoverride);

        return Inertia::modal('Roles/Edit', compact('role', 'roles'))
            ->baseRoute('users.role.index');
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        $data = $request->validated();

        $data['allowoverride'] = collect($data['allowoverride'])->join(",");
        $role->update($data);

        return redirect()->route('users.role.index')
            ->withStatus(AlertResponseService::updateSuccess('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        try {
            $hasRelation = Role::query()
                ->where('id', $role->id)
                ->WhereHas('users')
                ->exists();

            if ($hasRelation) {
                throw new RelationshipException();
            }

            $role->delete();
        } catch (RelationshipException) {
            return redirect()->back()
                ->withStatus(AlertResponseService::deletionHasRelation('role'));
        }
        return redirect()->back()
            ->withStatus(AlertResponseService::deletionSuccess('role'));
    }
}
