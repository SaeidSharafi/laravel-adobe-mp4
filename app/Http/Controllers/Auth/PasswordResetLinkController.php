<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetLinkRequest;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request)
    {
        //$request->validated();

        $data = $request->getValidatedData();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $user = User::query()->with('otp')->where($data)->first();

        $status = Password::sendResetLink($data);

        if (Password::RESET_LINK_SENT === $status) {
            return redirect()->route('password.verify.show', $data);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
