<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login'); // Blade view: resources/views/auth/login.blade.php
    }

    // Handle login submission
    public function login(Request $request)
    {
        // Validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        //echo 'Asif';
       // die();

        $credentials = $request->only('email', 'password');

        // Attempt login
 if (Auth::attempt($credentials)) {
    $request->session()->regenerate();
    dd(
            'Login Successful',
            'Auth check:', Auth::guard('admin')->check(),
            'User:', Auth::guard('admin')->user(),
            'Session all:', $request->session()->all()
        );
   // return redirect()->intended('/admin/dashboard');
}

        

        // Login failed: back with error and old input
        return back()->withInput($request->only('email'))
                     ->with('error', 'Invalid email or password.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
