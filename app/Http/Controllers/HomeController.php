<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Fungsi __construct() beserta isinya sudah dihapus agar tidak error di Laravel 11

    public function index()
    {
        // Ambil data tipe user yang sedang login
        $role = Auth::user()->usertype;

        // 1. Jika KASIR, tendang ke halaman Transaksi
        if ($role == 'kasir') {
            return redirect('/transaksi');
        }

        // 2. Jika USER (Pembeli Biasa), tendang ke Halaman Katalog Depan
        if ($role == 'user') {
            return redirect('/'); 
            // Catatan: Jika halaman katalog pembeli milikmu menggunakan URL lain, 
            // misalnya '/katalog', silakan ganti tanda '/' menjadi '/katalog'
        }

        // 3. Jika ADMIN, barulah tampilkan halaman Dashboard Admin
        return view('home');
    }
}