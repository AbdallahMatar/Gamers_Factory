<?php

namespace App\Http\Controllers\Cms\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    //
    public function showLoginView()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        if (Auth::guard('admin_web')->attempt($credentials)) {
            $admin = Auth::guard('admin_web')->user();
            if ($admin->status == 'Active') {
                return redirect(route('admin.dashboard'));
            } else {
                return redirect(route('admin.error-500'));
            }
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin_web')->logout();
        $request->session()->invalidate();
        return redirect()->guest(route('admin.login_view'));
    }
}
