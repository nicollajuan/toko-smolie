<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // Pastikan ini ada

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // KOREKSI: Hapus 'profile.' karena file ada di root folder views
        return view('profile', compact('user')); 
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi
        $request->validate([
            'name'  => 'required|string|max:255',
            // Validasi email ini penting agar tidak error duplicate entry milik sendiri
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'password' => 'nullable|min:8|confirmed',
        ], [
            // Custom pesan error (Opsional, agar lebih jelas)
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // 2. Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Update Password (Hanya jika diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Update Foto Profil
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && File::exists(public_path('img/user/'.$user->foto))) {
                File::delete(public_path('img/user/'.$user->foto));
            }

            // Pastikan folder img/user ada
            if (!File::exists(public_path('img/user'))) {
                File::makeDirectory(public_path('img/user'), 0755, true);
            }

            // Upload foto baru
            $file = $request->file('foto');
            // Gunakan time() untuk menghindari nama file kembar
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('img/user'), $filename);
            
            $user->foto = $filename;
        }

        // Simpan ke Database
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}