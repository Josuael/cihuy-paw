<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;
use App\Models\User;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            

            $request->session()->regenerate();

            // user yang baru berhasil login
            $user = Auth::user();

            AuditLogService::log(
                'Login',
                'users',
                $user->user_id,
                [],
                ['username' => $user->username]
            );

            return redirect()->intended('/' . Auth::user()->role . '/dashboard')
                             ->with('success', 'Login berhasil!');
        }

            AuditLogService::log('Login', 'users', 0, [], [
                'username' => $request->username,
                'success'  => false,
            ]);


            return back()->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLogService::log(
                'Logout',
                'users',
                $user->user_id,
                [],
                []
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
