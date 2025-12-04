<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the employee dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $organization = $user->organization;

        return view('dashboard.index', compact('user', 'organization'));
    }
}
