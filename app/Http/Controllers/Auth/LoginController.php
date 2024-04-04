<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function authCheck(){
        $auth_id = Auth::guard('admin')->user()->id;
        return response()->json([
            'data' => $auth_id,
            'status' => 'success'
        ]);

    }

    public function login(LoginRequest $request)
    {
        // Validate the request using the LoginRequest

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication successful
            return redirect()->intended('/dashboard');
        }

        // Authentication failed
        return redirect()->back()->with(['error' => 'Invalid Email or Password!']);
    }

    public function loginPage(){
        return view('vue-pages.auth.login');
    }

    public function logout(){
        logger('Before logout: ' . Auth::guard('admin')->check());
        Auth::guard('admin')->logout();
        logger('After logout: ' . Auth::guard('admin')->check());
        $rediretTo = "/admin/login";
        return response()->json([
            'status' => 'success',
            'message' => 'Logout Success',
            'redirect' => $rediretTo
        ]);
    }
}
