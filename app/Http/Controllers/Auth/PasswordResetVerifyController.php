<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetVerifyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetVerifyController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render(
            'Auth/ForgotPasswordVerify',
            request()?->only(['phone', 'email'])
        );
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
    public function store(PasswordResetVerifyRequest $request)
    {
        $token = $request->validateResetRequest();


        return redirect()->route('password.reset', $token);
    }
}
