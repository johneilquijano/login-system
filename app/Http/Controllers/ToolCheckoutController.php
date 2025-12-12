<?php

namespace App\Http\Controllers;

use App\Models\ToolCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolCheckoutController extends Controller
{
    public function index()
    {
        $checkouts = Auth::user()->toolCheckouts()->orderBy('checked_out_date', 'desc')->paginate(10);
        return view('employee.tools.index', compact('checkouts'));
    }

    public function show(ToolCheckout $toolCheckout)
    {
        if ($toolCheckout->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.tools.show', compact('toolCheckout'));
    }
}
