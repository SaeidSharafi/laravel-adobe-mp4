<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\AlertResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        $user = UserResource::make(auth()->user());

        return inertia('Profile/View', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit()
    {
        $user = UserResource::make(auth()->user());

        return inertia('Profile/Edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProfileRequest $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        if ( ! $data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('profile.view')
            ->with('status', AlertResponseService::success(__('response.user.profile.update.success')));
    }
}
