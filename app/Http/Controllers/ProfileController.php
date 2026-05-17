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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'whatsapp' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ];

        if ($user->usertype === 'admin') {
            $rules['whatsapp'] = [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '') {
                        $cleaned = preg_replace('/[^0-9]/', '', $value);
                        if (strlen($cleaned) < 8 || strlen($cleaned) > 12) {
                            $fail('Nomor WhatsApp harus 8-12 digit. Contoh: 8573193271');
                        }
                    }
                }
            ];
            $rules['nama_bank']              = 'nullable|string|max:100';
            $rules['nomor_rekening']         = 'nullable|string|max:30';
            $rules['nama_pemilik_rekening']  = 'nullable|string|max:255';
            $rules['gambar_qris']            = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules, [
            'email.unique'        => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'foto.max'            => 'Ukuran foto maksimal 2MB.',
            'gambar_qris.max'     => 'Ukuran gambar QRIS maksimal 2MB.',
        ]);

        // 2. Update Data Dasar
        $user->name  = $request->name;
        $user->email = $request->email;

        // 3. Update Password (Hanya jika diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Update WhatsApp, Bank & QRIS (Khusus Admin)
        if ($user->usertype === 'admin') {
            // Auto-format WhatsApp
            if ($request->whatsapp) {
                $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
                if (substr($whatsapp, 0, 4) === '6262') {
                    $whatsapp = substr($whatsapp, 2);
                }
                if (substr($whatsapp, 0, 2) === '62') {
                    $user->whatsapp = '+' . $whatsapp;
                } else if (substr($whatsapp, 0, 1) === '0') {
                    $user->whatsapp = '+62' . substr($whatsapp, 1);
                } else {
                    $user->whatsapp = '+62' . $whatsapp;
                }
            } else {
                $user->whatsapp = null;
            }

            $user->nama_bank             = $request->nama_bank;
            $user->nomor_rekening        = $request->nomor_rekening;
            $user->nama_pemilik_rekening = $request->nama_pemilik_rekening;

            // Upload Gambar QRIS
            if ($request->hasFile('gambar_qris')) {
                // Hapus gambar lama jika ada
                if ($user->gambar_qris && File::exists(public_path('img/qris/' . $user->gambar_qris))) {
                    File::delete(public_path('img/qris/' . $user->gambar_qris));
                }

                // Buat folder jika belum ada
                if (!File::exists(public_path('img/qris'))) {
                    File::makeDirectory(public_path('img/qris'), 0755, true);
                }

                $file     = $request->file('gambar_qris');
                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/qris'), $filename);

                $user->gambar_qris = $filename;
            }
        }

        // 5. Update Foto Profil
        if ($request->hasFile('foto')) {
            if ($user->foto && File::exists(public_path('img/user/' . $user->foto))) {
                File::delete(public_path('img/user/' . $user->foto));
            }

            if (!File::exists(public_path('img/user'))) {
                File::makeDirectory(public_path('img/user'), 0755, true);
            }

            $file     = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('img/user'), $filename);

            $user->foto = $filename;
        }

        // Simpan ke Database
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}