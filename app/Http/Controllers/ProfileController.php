<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

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
        $rules = [
            'name'  => 'required|string|max:255',
            // Validasi email ini penting agar tidak error duplicate entry milik sendiri
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'password' => 'nullable|min:8|confirmed',
        ];

        // Tambah validasi WhatsApp jika user adalah admin
        if ($user->usertype === 'admin') {
            // Gunakan custom callback untuk WhatsApp validation
            $rules['whatsapp'] = [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '') {
                        // Remove semua non-digit
                        $cleaned = preg_replace('/[^0-9]/', '', $value);
                        
                        // Check format: minimum 8 digit, maksimal 12 digit
                        if (strlen($cleaned) < 8 || strlen($cleaned) > 12) {
                            $fail('Nomor WhatsApp harus 8-12 digit. Contoh: 8573193271');
                        }
                    }
                }
            ];
            $rules['nama_bank'] = 'nullable|string|max:100';
            $rules['nomor_rekening'] = 'nullable|string|max:30';
            $rules['nama_pemilik_rekening'] = 'nullable|string|max:255';
        }

        $request->validate($rules, [
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

        // 4. Update WhatsApp & Bank (Khusus Admin)
        if ($user->usertype === 'admin') {
            // Auto-format WhatsApp dengan +62 prefix
            if ($request->whatsapp) {
                // Remove semua karakter non-digit
                $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
                
                // Clean up double 62
                if (substr($whatsapp, 0, 4) === '6262') {
                    $whatsapp = substr($whatsapp, 2); // Remove duplicate 62
                }
                
                // Jika sudah ada 62 di depan, gunakan as-is
                if (substr($whatsapp, 0, 2) === '62') {
                    $user->whatsapp = '+' . $whatsapp;
                } else if (substr($whatsapp, 0, 1) === '0') {
                    // Jika dimulai dengan 0, ganti dengan 62
                    $user->whatsapp = '+62' . substr($whatsapp, 1);
                } else {
                    // Format normal: tambahkan 62 di depan (8573193271 → +628573193271)
                    $user->whatsapp = '+62' . $whatsapp;
                }
            } else {
                $user->whatsapp = null;
            }
            
            $user->nama_bank = $request->nama_bank;
            $user->nomor_rekening = $request->nomor_rekening;
            $user->nama_pemilik_rekening = $request->nama_pemilik_rekening;
        }

        // 5. Update Foto Profil
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

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui! WhatsApp Admin ikut terupdate di Dashboard.');
    }
}