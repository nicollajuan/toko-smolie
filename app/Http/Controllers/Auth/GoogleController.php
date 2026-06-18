<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('social_id', $googleUser->getId())
            ->where('social_provider', 'google')
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'social_id' => $googleUser->getId(),
                'social_provider' => 'google',
                'password' => bcrypt(Str::random(16)),
                'usertype' => 'user', 
                'email_verified_at' => now(),
            ]);
        } else {
            if (!$user->social_id) {
                $user->update([
                    'social_id' => $googleUser->getId(),
                    'social_provider' => 'google',
                ]);
            }
        }

        Auth::login($user);

        if (
            $user->usertype === 'user' &&
            (empty($user->username) || empty($user->jenis_kelamin) || empty($user->no_hp) || empty($user->alamat))
        ) {
            return redirect()->route('complete-profile');
        }

        return redirect()->route('pembeli.index');
    }
}