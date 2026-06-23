<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfilController extends Controller
{
    public function index()
    {
        return view('editprofil');
    }

    public function update(Request $request)
    {
            $user = Auth::user();

            // Validasi dasar
            $rules = [
                'fullname' => 'required',
                'password' => 'nullable|min:8|confirmed',
                'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            // Jika role user adalah 'user', email wajib
            if ($user->role === 'pelapor') {
                $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            } else if ($user->role === 'admin') {
                // Kalau admin, email boleh kosong, tapi kalau diisi harus valid format email
                $rules['email'] = 'nullable|email|unique:users,email,' . $user->id;
            }

            $request->validate($rules, [
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
                'password.confirmed' => 'Password tidak cocok.',
                'password.min' => 'Password minimal harus 8 karakter.',
            ]);

            // Update data user
            $user->fullname = $request->fullname;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = $request->password;
            }

            if ($request->hasFile('profile')) {
                if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                    Storage::disk('public')->delete($user->profile);
                }
                $imagePath = $request->file('profile')->store('profiluser', 'public');
                $user->profile = $imagePath;
            }

            $user->save();

            return redirect()->route('editprofil.update')->with('success', 'Profil berhasil diperbarui');
    }
}
