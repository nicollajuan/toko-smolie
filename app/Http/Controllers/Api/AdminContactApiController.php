<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\AdminHelper;
use App\Models\User;

/**
 * API Controller untuk menyediakan informasi kontak admin
 * 
 * Berguna untuk:
 * - Mobile apps (React Native, Flutter)
 * - Single Page Applications (Vue, React)
 * - Progressive Web Apps
 * 
 * Usage:
 * GET /api/admin/contact-info
 * GET /api/admin/whatsapp
 */
class AdminContactApiController extends Controller
{
    /**
     * Get informasi kontak admin lengkap
     * 
     * GET /api/admin/contact-info
     * 
     * Response:
     * {
     *   "success": true,
     *   "data": {
     *     "name": "Nama Admin",
     *     "email": "admin@smolie.com",
     *     "whatsapp": "+6281234567890",
     *     "whatsapp_link": "https://wa.me/6281234567890",
     *     "no_hp": "081234567890",
     *     "alamat": "Jl. Raya No. 123",
     *     "foto": "admin_photo.jpg"
     *   }
     * }
     */
    public function getContactInfo()
    {
        $contactInfo = AdminHelper::getAdminContactInfo();

        if (!$contactInfo['name']) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $contactInfo,
        ]);
    }

    /**
     * Get nomor WhatsApp admin
     * 
     * GET /api/admin/whatsapp
     * 
     * Response:
     * {
     *   "success": true,
     *   "whatsapp": "+6281234567890",
     *   "whatsapp_formatted": "6281234567890",
     *   "whatsapp_link": "https://wa.me/6281234567890",
     *   "has_whatsapp": true
     * }
     */
    public function getWhatsApp()
    {
        $whatsapp = AdminHelper::getAdminWhatsApp();

        if (!$whatsapp) {
            return response()->json([
                'success' => false,
                'message' => 'Admin belum menambahkan nomor WhatsApp',
                'has_whatsapp' => false,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'whatsapp' => $whatsapp,
            'whatsapp_formatted' => AdminHelper::getAdminWhatsAppFormatted(),
            'whatsapp_link' => AdminHelper::getAdminWhatsAppLink(),
            'has_whatsapp' => true,
        ]);
    }

    /**
     * Get WhatsApp link dengan custom message
     * 
     * GET /api/admin/whatsapp-link?message=Hello%20Admin
     * 
     * Query Parameters:
     * - message: Text message yang akan dikirim
     * 
     * Response:
     * {
     *   "success": true,
     *   "whatsapp_link": "https://wa.me/6281234567890?text=Hello%20Admin"
     * }
     */
    public function getWhatsAppLink()
    {
        $message = request()->query('message');
        $link = AdminHelper::getAdminWhatsAppLink($message);

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Admin belum menambahkan nomor WhatsApp',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'whatsapp_link' => $link,
        ]);
    }

    /**
     * Get admin data lengkap (untuk dashboard)
     * 
     * GET /api/admin/info
     * Memerlukan auth (middleware 'auth:api')
     * 
     * Response:
     * {
     *   "success": true,
     *   "admin": {
     *     "id": 1,
     *     "name": "Nama Admin",
     *     "email": "admin@smolie.com",
     *     "whatsapp": "+6281234567890",
     *     "foto": "admin_photo.jpg",
     *     "no_hp": "081234567890",
     *     "alamat": "Jl. Raya",
     *     "nama_bank": "BCA",
     *     "nomor_rekening": "123456789",
     *     "nama_pemilik_rekening": "Admin Name"
     *   }
     * }
     */
    public function getAdminInfo()
    {
        $admin = AdminHelper::getAdmin();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'admin' => $admin->only([
                'id', 'name', 'email', 'whatsapp', 'foto',
                'no_hp', 'alamat', 'nama_bank',
                'nomor_rekening', 'nama_pemilik_rekening'
            ]),
        ]);
    }

    /**
     * Check apakah admin memiliki WhatsApp
     * 
     * GET /api/admin/has-whatsapp
     * 
     * Response:
     * {
     *   "success": true,
     *   "has_whatsapp": true
     * }
     */
    public function hasWhatsApp()
    {
        return response()->json([
            'success' => true,
            'has_whatsapp' => AdminHelper::hasAdminWhatsApp(),
        ]);
    }
}
