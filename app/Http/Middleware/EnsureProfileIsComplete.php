<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->usertype === 'pembeli' && (
            empty($user->username) ||
            empty($user->jenis_kelamin) ||
            empty($user->no_hp) ||
            empty($user->alamat)
        )) {
            if (!$request->routeIs('complete-profile', 'complete-profile.store', 'logout')) {
                return redirect()->route('complete-profile');
            }
        }

        return $next($request);
    }
}