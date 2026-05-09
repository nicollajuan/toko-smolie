<?php

namespace App\Helpers;

use App\Models\User;

class AdminHelper
{
    /**
     * Dapatkan nomor WhatsApp admin utama
     * 
     * @return string|null
     */
    public static function getAdminWhatsApp(): ?string
    {
        $admin = User::where('usertype', 'admin')
                    ->whereNotNull('whatsapp')
                    ->first();
        
        return $admin?->whatsapp;
    }

    /**
     * Dapatkan WhatsApp dalam format untuk link WhatsApp
     * Contoh: 6281234567890 (untuk https://wa.me/6281234567890)
     * 
     * @return string|null
     */
    public static function getAdminWhatsAppFormatted(): ?string
    {
        $whatsapp = self::getAdminWhatsApp();
        
        if (!$whatsapp) {
            return null;
        }

        // Hapus semua karakter non-angka
        $formatted = preg_replace('/[^0-9]/', '', $whatsapp);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (str_starts_with($formatted, '0')) {
            $formatted = '62' . substr($formatted, 1);
        }
        
        // Jika tidak ada 62 di depan, tambahkan
        if (!str_starts_with($formatted, '62')) {
            $formatted = '62' . $formatted;
        }

        return $formatted;
    }

    /**
     * Dapatkan link WhatsApp untuk chat
     * 
     * @param string $message Pesan yang ingin dikirim (opsional)
     * @return string|null
     */
    public static function getAdminWhatsAppLink($message = null): ?string
    {
        $whatsapp = self::getAdminWhatsAppFormatted();
        
        if (!$whatsapp) {
            return null;
        }

        $baseUrl = "https://wa.me/{$whatsapp}";
        
        if ($message) {
            $baseUrl .= "?text=" . urlencode($message);
        }

        return $baseUrl;
    }

    /**
     * Dapatkan semua data admin
     * 
     * @return User|null
     */
    public static function getAdmin(): ?User
    {
        return User::where('usertype', 'admin')->first();
    }

    /**
     * Cek apakah admin memiliki WhatsApp
     * 
     * @return bool
     */
    public static function hasAdminWhatsApp(): bool
    {
        return !is_null(self::getAdminWhatsApp());
    }

    /**
     * Dapatkan informasi kontak admin lengkap
     * 
     * @return array
     */
    public static function getAdminContactInfo(): array
    {
        $admin = self::getAdmin();

        return [
            'name' => $admin?->name,
            'email' => $admin?->email,
            'whatsapp' => $admin?->whatsapp,
            'whatsapp_formatted' => self::getAdminWhatsAppFormatted(),
            'whatsapp_link' => self::getAdminWhatsAppLink(),
            'no_hp' => $admin?->no_hp,
            'alamat' => $admin?->alamat,
            'foto' => $admin?->foto,
        ];
    }
}
