<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PraktikumController extends Controller
{
    public function home(){
        return view('home');
    }

    public function product(){
        return view('produk');
    }

    public function transaction(){
        return view('transaksi');
    }

    public function report(){
        return view('laporan');
    }
}