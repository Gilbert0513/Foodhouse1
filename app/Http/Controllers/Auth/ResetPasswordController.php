<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset');
    }

    public function reset(Request $request)
    {
        // Simple implementation for demo
        return redirect()->route('login')->with('status', 'Password reset successful!');
    }
}