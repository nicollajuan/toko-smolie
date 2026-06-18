<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class CompleteProfileController extends Controller
{
    public function show()
    {
        return view('auth.complete-profile');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['required', 'numeric'],
            'alamat' => ['required', 'string', 'max:500'],
        ]);

        $user->update($request->only(['username', 'jenis_kelamin', 'no_hp', 'alamat']));

        return redirect()->route('pembeli.index')->with('status', 'Profil berhasil dilengkapi!');
    }

    public function destroy(Request $request)
{
    $user = Auth::user();

    $request->validateWithBag('deleteAccount', [
        'password' => 'required',
    ]);

    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password yang Anda masukkan salah.'], 'deleteAccount');
    }

    // Hapus foto profil jika ada
    if ($user->foto && File::exists(public_path('img/user/' . $user->foto))) {
        File::delete(public_path('img/user/' . $user->foto));
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}