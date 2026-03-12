<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // 1. TAMBAHKAN ATURAN VALIDASI UNTUK KOLOM BARU
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'jenis_kelamin' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => $this->passwordRules(),
        ])->validate();

        // 2. MASUKKAN DATA KE DALAM DATABASE
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'jenis_kelamin' => $input['jenis_kelamin'],
            'alamat' => $input['alamat'],
            'no_hp' => $input['no_hp'],
            'password' => Hash::make($input['password']),
            'usertype' => 'user', // Otomatis mendaftar sebagai pembeli (user)
        ]);
    }
}