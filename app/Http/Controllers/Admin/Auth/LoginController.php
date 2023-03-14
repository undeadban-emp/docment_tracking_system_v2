<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function submit(Request $request)
    {
        if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['message' => 'Please check your username or password']);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}