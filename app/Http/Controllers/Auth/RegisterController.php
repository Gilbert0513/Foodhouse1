<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:customer,admin,cashier',
            'access_key' => 'nullable|string'
        ]);

        // 🔐 STAFF ACCESS KEY CHECK
        if (in_array($request->role, ['admin', 'cashier'])) {
            if ($request->access_key !== env('STAFF_ACCESS_KEY')) {
                return back()
                    ->withErrors(['access_key' => 'Invalid access key.'])
                    ->withInput();
            }
        }

        // Generate username
        $username = strtolower(str_replace(' ', '_', $request->full_name)) . '_' . rand(100, 999);

        $user = User::create([
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => 'active',
        ]);

        Auth::login($user);

        // 🔁 ROLE-BASED REDIRECT
        $redirect = match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'cashier' => redirect()->route('cashier.pos'),
            default => redirect()->route('customer.menu'),
        };

        return $redirect->with('success', 'Registration successful! Welcome to Foodhouse.');
    }
}
