<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\Auth\User;
use App\Services\AlertResponseService;
use App\Services\ImportCSVService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserImportController extends Controller
{
    public function __construct(
        private readonly ImportCSVService $importCSVService
    ) {
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Users/Import');
    }

    public function store(Request $request): Response|RedirectResponse
    {
        $this->authorize('create', User::class);


        $file = $request->file('files');
        if ( ! $file) {
            abort(404);
        }
        $file       = $file[0]['file'];
        $users      = new UserImport($request->get('confirmed', false));
        $data       = $this->importCSVService->import(
            $users,
            $file,
            $request->get('confirmed', false),
        );

        if (null === $data) {
            return redirect()->route('users.user.index')
                ->withStatus(AlertResponseService::success('Import Queued'));
        }

        return Inertia::render('Users/Import', ['rows'                    => $data,
            'headers'                                                     => [
                $users->headers->first_name => __('auth.user.first_name'),
                $users->headers->last_name  => __('auth.user.last_name'),
                $users->headers->phone      => __('auth.user.phone'),
                $users->headers->email      => __('auth.user.email'),
                $users->headers->password   => __('auth.user.password'),
                $users->headers->is_admin   => __('auth.user.is_admin'),
            ], 'validated' => $users->failures()->isEmpty()]);
    }
}
