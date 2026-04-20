<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class FacebookAuthController extends Controller
{
    /**
     * Redirect user ke Facebook untuk login
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback setelah user login
     */
    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->fields(['name', 'email', 'picture.width(300).height(300)'])
                ->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Facebook. Silakan coba lagi.');
        }

        // Cari user berdasarkan social_id
        $user = User::where('social_id', $facebookUser->id)
                    ->where('social_provider', 'facebook')
                    ->first();

        // Jika user belum ada, buat user baru
        if (!$user) {
            // Cek apakah email sudah terdaftar
            $existingUser = User::where('email', $facebookUser->email)->first();

            if ($existingUser) {
                // Update existing user dengan social login info
                $existingUser->update([
                    'social_id' => $facebookUser->id,
                    'social_provider' => 'facebook',
                    'profile_photo' => $this->downloadProfilePhoto($facebookUser),
                ]);
                $user = $existingUser;
            } else {
                // Buat user baru
                $user = User::create([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'social_id' => $facebookUser->id,
                    'social_provider' => 'facebook',
                    'password' => bcrypt(Str::random(16)), // Random password
                    'usertype' => 'pembeli', // Default sebagai pembeli
                    'username' => $this->generateUsername($facebookUser->name),
                    'profile_photo' => $this->downloadProfilePhoto($facebookUser),
                ]);
            }
        }

        // Login user
        Auth::login($user, remember: true);

        return redirect()->intended('/');
    }

    /**
     * Download dan simpan foto profil dari Facebook
     */
    private function downloadProfilePhoto($facebookUser)
    {
        try {
            $photoUrl = $facebookUser->getAvatar();
            
            if (!$photoUrl) {
                return null;
            }

            $filename = 'profile_' . $facebookUser->id . '_' . time() . '.jpg';
            $path = 'img/user/' . $filename;

            // Download foto
            $photo = file_get_contents($photoUrl);
            
            // Simpan ke storage
            if (!file_exists(public_path('img/user'))) {
                mkdir(public_path('img/user'), 0755, true);
            }
            
            file_put_contents(public_path($path), $photo);

            return $filename;
        } catch (\Exception $e) {
            // Jika gagal download, return null
            return null;
        }
    }

    /**
     * Generate username unik dari nama
     */
    private function generateUsername($name)
    {
        $baseUsername = Str::slug(Str::lower($name), '');
        $username = $baseUsername;
        $counter = 1;

        // Cek apakah username sudah ada
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
