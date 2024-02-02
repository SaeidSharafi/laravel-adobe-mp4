<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        if ($redirect = DashboardService::redirectToDashboard(auth()->user())) {
            dd($redirect);
            return $redirect;
        }


        return Inertia::render('Dashboard');
    }
}
