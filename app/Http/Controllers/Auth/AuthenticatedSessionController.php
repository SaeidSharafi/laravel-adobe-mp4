<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\OtpService;
use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  LoginRequest  $request
     *
     * @return RedirectResponse
     */
    public function authenticateUsername(LoginRequest $request)
    {
        $user = $request->checkUsername();

        return redirect()->route('password', ['username' => $request->username]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  LoginRequest  $request
     *
     * @return Response
     */
    public function showPassword(LoginRequest $request)
    {
        $user = $request->checkUsername();

        $otp = null;
        if ( ! $user?->password) {
            $otp = OtpService::generateOTP($user);
        }

        return Inertia::render('Auth/Password', [
            'otp'              => App::environment(['local', 'staging']) ? $otp?->token : '',
            'username'         => $request->username,
            'useOTP'           => ! $user?->password,
            'canResetPassword' => Route::has('password.request'),
            'status'           => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  LoginRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return DashboardService::redirectToDashboard(auth()->user(), redirectToDashboard: true);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
