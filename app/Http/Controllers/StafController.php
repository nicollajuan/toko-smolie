<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StafController extends Controller
{
    // 1. Tampilkan Halaman Daftar Staf
    public function index()
    {
        // Proteksi Ketat: Hanya usertype admin yang bisa masuk
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized. Hanya Admin yang dapat mengakses halaman ini.');
        }

        // Ambil data user yang usertype-nya admin atau kasir
        $dataStaf = User::whereIn('usertype', ['admin', 'kasir'])->orderBy('usertype', 'asc')->get();

        return view('staf.index', compact('dataStaf'));
    }

    // 2. Simpan Staf/Kasir Baru
    public function store(Request $request)
    {
        if (Auth::user()->usertype !== 'admin') { abort(403); }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'usertype' => 'required|in:admin,kasir',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,
        ]);

        return redirect()->back()->with('success', 'Akun staf baru berhasil dibuat!');
    }

    // 3. Update Data Staf
    public function update(Request $request, $id)
    {
        if (Auth::user()->usertype !== 'admin') { abort(403); }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'usertype' => 'required|in:admin,kasir',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'usertype' => $request->usertype,
        ]);

        // Jika password baru diisi, update password-nya
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->back()->with('success', 'Data staf berhasil diperbarui!');
    }

    // 4. Hapus Staf
    public function destroy($id)
    {
        if (Auth::user()->usertype !== 'admin') { abort(403); }

        // Keamanan: Cegah admin tidak sengaja menghapus akunnya sendiri
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Akun staf berhasil dihapus!');
    }
}