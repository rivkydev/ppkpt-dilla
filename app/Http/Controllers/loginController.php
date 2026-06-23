<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verify;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

       if (auth()->attempt(['username' => $request->username, 'password' => $request->password])) {
        $user = auth()->user(); // Ambil data user yang berhasil login

        if ($user->role === 'admin') {
            return redirect('/admin');
        } elseif ($user->role === 'pelapor') {
            return redirect('/user');
        } elseif ($user->role === 'satgas') {
            return redirect('/satgas');
        } else {
            // Jika role tidak dikenal, arahkan ke halaman default
            return redirect('/');
        }
    }

    return redirect('/login')->with('error', 'Username atau password yang Anda masukkan salah.');
    }   

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
