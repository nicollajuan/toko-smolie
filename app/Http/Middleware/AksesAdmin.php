<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AksesAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Pastikan sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userType = Auth::user()->usertype;

        // ==========================================
        // 2. KUNCI UNTUK PEMBELI (USER)
        // ==========================================
        if ($userType == 'user') {
            if ($request->is('home')) {
                return redirect('/'); 
            }
            return redirect('/')->with('error', 'Akses Ditolak! Halaman tersebut hanya untuk Admin dan Kasir.');
        }

        // ==========================================
        // 3. KUNCI KETAT UNTUK KASIR (ROLE: KASIR)
        // ==========================================
        if ($userType == 'kasir') {
            // Daftar URL yang DIIZINKAN untuk Kasir (Tanda * berarti termasuk sub-halamannya)
            $allowedForKasir = [
                '/',             // <-- AKSES DIBUKA: Halaman utama / Katalog depan
                'home',
                'kasir*',
                'katalog*',
                'transaksi*',
                'logout*',
                'cart*',         // <-- AKSES DIBUKA: Halaman keranjang
                'add-to-cart*',  // <-- AKSES DIBUKA: Fungsi tambah keranjang
                'update-cart*',  // <-- AKSES DIBUKA: Fungsi update jumlah barang
                'remove-cart*',  // <-- AKSES DIBUKA: Fungsi hapus barang dari keranjang
                'checkout*',     // <-- AKSES DIBUKA: Fungsi proses pesanan
                'pembayaran*',   // <-- AKSES DIBUKA: Halaman pembayaran/struk
                'set-layanan*'   // <-- AKSES DIBUKA: Pilihan Takeaway / Delivery
            ];

            $isAllowed = false;
            foreach ($allowedForKasir as $path) {
                if ($request->is($path)) {
                    $isAllowed = true;
                    break;
                }
            }

            // Jika Kasir mencoba masuk ke halaman terlarang (seperti /laporan, /produk, dsb)
            if (!$isAllowed) {
                return redirect('/home')->with('error', 'Akses Ditolak! Anda login sebagai Kasir, halaman tersebut khusus Admin.');
            }
        }

        // ==========================================
        // 4. JIKA ADMIN (Bebas mengakses semuanya)
        // ==========================================
        return $next($request);
    }
}