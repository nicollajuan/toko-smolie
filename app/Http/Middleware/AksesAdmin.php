<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AksesAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika yang login adalah admin atau kasir, silakan masuk!
        if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            return $next($request);
        }

        // Jika yang login adalah user/pembeli biasa, usir (redirect) ke halaman katalog depan!
        return redirect('/')->with('error', 'Maaf, halaman tersebut khusus Admin/Kasir.');
    }
}