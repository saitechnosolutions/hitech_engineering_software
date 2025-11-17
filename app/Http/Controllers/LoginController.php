<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
        public function login(Request $request)
        {
            try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];


            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return redirect('/dashboard')->with('message', 'User Login Successfully...');
            }
                return back()->with('error', 'Credentials are incorrect...');
            } catch (\Exception $e) {
                Log::error('Login error: ' . $e->getMessage());

                return back()->with('error', 'Something went wrong. Please try again.');
            }
        }

            public function logout()
            {
                Auth::logout();
                return redirect('/');
            }
}
