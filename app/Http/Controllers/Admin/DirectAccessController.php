<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DirectAccessController extends Controller
{
    /**
     * Show the direct access token page
     */
    public function show()
    {
        $user = Auth::user();
        $token = $user->getDirectAccessToken();
        $directAccessUrl = url('/admin-direct-access?token=' . $token);

        return view('admin.direct-access.show', compact('token', 'directAccessUrl', 'user'));
    }

    /**
     * Regenerate the direct access token
     */
    public function regenerate(Request $request)
    {
        $user = Auth::user();
        $user->regenerateDirectAccessToken();

        return redirect()->route('admin.direct-access.show')
            ->with('success', 'Direct access link regenerated successfully!');
    }
}
