<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Fungsi __construct() beserta isinya sudah dihapus agar tidak error di Laravel 11

    public function index()
    {
        // Cek Role User
        $role = Auth::user()->usertype;

        if ($role == 'user') {
            // Jika Pembeli/User, lempar ke Halaman Katalog Depan
            return redirect()->route('pembeli.index');
        } else {
            // Jika Admin/Kasir, masuk ke Dashboard
            return view('home');
        }
    }
}