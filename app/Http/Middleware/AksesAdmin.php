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
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. KUNCI RBAC UNTUK PEMBELI (USERTYPE == 'user')
        if (Auth::user()->usertype == 'user') {
            
            // CEK JALUR: Jika sistem default Laravel melempar ke '/home' setelah login
            if ($request->is('home')) {
                // Pindahkan ke beranda dengan mulus TANPA pesan error
                return redirect('/'); 
            }

            // CEK JALUR: Jika user mencoba memaksa masuk ke rute admin lainnya (/transaksi, dll)
            // Lemparkan pesan error merah!
            return redirect('/')->with('error', 'Akses Ditolak! Halaman tersebut hanya untuk Admin dan Kasir.');
        }

        // 3. Jika lolos (berarti dia admin atau kasir), izinkan masuk
        return $next($request);
    }
}