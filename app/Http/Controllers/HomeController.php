<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Memastikan hanya yang login yang bisa akses
    public function __construct()
    {
        // Jika kamu pakai Laravel versi baru, middleware bisa diatur di route, 
        // tapi baris ini aman untuk memastikan keamanan.
        //$this->middleware('auth'); 
    }

    public function index()
    {
        // Cek Role User
        $role = Auth::user()->usertype;

        if ($role == 'pembeli') {
            // Jika Pembeli, lempar ke Halaman Menu Utama
            return redirect()->route('pembeli.index');
        } else {
            // Jika Admin/Kasir, masuk ke Dashboard
            return view('home');
        }
    }
}