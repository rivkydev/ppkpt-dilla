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
            if ($user->status_verify == 0) {
                $otp = rand(1000, 9999);
                $verify = Verify::create([
                    'user_id' => $user->id,
                    'otp' => md5($otp),
                    'expired_at' => now()->addMinutes(2),
                ]);

                Mail::to($user->email)->send(new OtpMail($otp));
                
                // Simpan expired timestamp ke session (milidetik)
                session(['otpTargetTime' => now()->addMinutes(2)->timestamp * 1000]);

                return redirect('/verify')->with('otpTargetTime', now()->addMinutes(2)->timestamp * 1000);
            } 
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
