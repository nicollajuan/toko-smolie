# 📱 PANDUAN IMPLEMENTASI LENGKAP
## Facebook OAuth Login & WhatsApp Admin Integration

**Tanggal**: April 27, 2026  
**Version**: 1.0  
**Status**: ✅ Siap Deploy

---

## 🎯 Ringkasan Fitur

Dokumentasi ini menjelaskan implementasi 2 fitur utama:

### 1️⃣ Facebook OAuth Login
- User dapat login menggunakan akun Facebook
- Auto-create akun jika email Facebook belum terdaftar
- Menyimpan foto profil dari Facebook
- Social ID & provider disimpan untuk identifikasi login sosial

### 2️⃣ WhatsApp Admin Integration
- Admin dapat mengubah nomor WhatsApp di Edit Profil
- Nomor WhatsApp ditampilkan di Dashboard untuk pembeli
- Pembeli dapat chat langsung ke WhatsApp admin
- Format validasi: `+62xxx` atau `08xxx`

---

## 📂 File-File yang Sudah Dibuat/Diubah

### ✅ Controllers
- `app/Http/Controllers/FacebookAuthController.php` - Sudah ada, siap pakai
- `app/Http/Controllers/ProfileController.php` - **UPDATE**: Tambah save WhatsApp
- `app/Http/Controllers/Api/AdminContactApiController.php` - **BARU**: API untuk WhatsApp

### ✅ Helpers & Utilities
- `app/Helpers/AdminHelper.php` - **BARU**: Helper untuk get WhatsApp Admin
- `app/Providers/AppServiceProvider.php` - **UPDATE**: Share AdminHelper ke views

### ✅ Models
- `app/Models/User.php` - Sudah memiliki fields: social_id, social_provider, whatsapp

### ✅ Views
- `resources/views/auth/login.blade.php` - Sudah ada Facebook button
- `resources/views/profile/edit.blade.php` - Sudah ada WhatsApp field
- `resources/views/examples/admin_helper_examples.blade.php` - **BARU**: Contoh penggunaan

### ✅ Routes
- `routes/web.php` - **UPDATE**: Tambah API routes untuk WhatsApp

### ✅ Migrations
- `database/migrations/2026_04_19_000000_add_social_and_whatsapp_to_users_table.php` - Sudah ada

### ✅ Config
- `config/services.php` - Sudah ada Facebook config
- `.env.example` - Sudah ada Facebook env variables

### ✅ Documentation
- `SETUP_FACEBOOK_WHATSAPP.md` - Panduan setup dasar
- `IMPLEMENTATION_GUIDE.md` - File ini

---

## 🚀 Langkah-Langkah Setup

### **STEP 1: Persiapan Awal**

```bash
# 1. Update composer dependencies
composer update

# 2. Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 3. Run migrations
php artisan migrate
```

### **STEP 2: Konfigurasi Facebook App**

Lihat bagian **"Facebook OAuth Setup"** di file `SETUP_FACEBOOK_WHATSAPP.md`

### **STEP 3: Update .env File**

```env
# Tambahkan ke .env Anda:
FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback

# Untuk production:
FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
```

### **STEP 4: Test Facebook Login**

```bash
# Jalankan dev server
php artisan serve

# Buka di browser: http://localhost:8000/login
# Klik tombol "ATAU MASUK DENGAN Facebook"
```

### **STEP 5: Test WhatsApp Update**

```bash
# Login sebagai admin
# Buka Profile → Edit Profil
# Isikan Nomor WhatsApp: +6281234567890
# Klik Simpan
```

---

## 💻 Penggunaan di Kode

### **Contoh 1: Tampilkan WhatsApp di View**

```blade
<!-- Cara 1: Menggunakan Global Share -->
@if($adminContactInfo['whatsapp_link'])
    <a href="{{ $adminContactInfo['whatsapp_link'] }}" target="_blank">
        <i class="bi bi-whatsapp"></i> Chat Admin
    </a>
@endif

<!-- Cara 2: Menggunakan Helper Class -->
@php
    $whatsappLink = \App\Helpers\AdminHelper::getAdminWhatsAppLink('Halo admin!');
@endphp

@if($whatsappLink)
    <a href="{{ $whatsappLink }}" target="_blank">Chat via WhatsApp</a>
@endif
```

### **Contoh 2: Di Controller**

```php
<?php

namespace App\Http\Controllers;

use App\Helpers\AdminHelper;

class SomeController extends Controller
{
    public function index()
    {
        // Get nomor WhatsApp admin
        $whatsapp = AdminHelper::getAdminWhatsApp();
        
        // Get info lengkap admin
        $adminInfo = AdminHelper::getAdminContactInfo();
        
        // Get link WhatsApp dengan pesan
        $link = AdminHelper::getAdminWhatsAppLink('Saya ingin pesan produk');
        
        // Check apakah ada WhatsApp
        if (AdminHelper::hasAdminWhatsApp()) {
            // Do something
        }
        
        return view('some.view', [
            'adminWhatsApp' => $whatsapp,
            'adminContactInfo' => $adminInfo,
            'whatsappLink' => $link,
        ]);
    }
}
```

### **Contoh 3: API Endpoints**

```javascript
// Get Admin Contact Info
fetch('/api/admin/contact-info')
    .then(res => res.json())
    .then(data => console.log(data.data));
// Response: { name, email, whatsapp, whatsapp_link, alamat, etc }

// Get WhatsApp Only
fetch('/api/admin/whatsapp')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const whatsappLink = data.whatsapp_link;
            // Open WhatsApp chat
            window.open(whatsappLink, '_blank');
        }
    });

// Get WhatsApp Link dengan Custom Message
fetch('/api/admin/whatsapp-link?message=Hello%20Admin')
    .then(res => res.json())
    .then(data => window.location.href = data.whatsapp_link);

// Check has WhatsApp
fetch('/api/admin/has-whatsapp')
    .then(res => res.json())
    .then(data => console.log('Has WhatsApp:', data.has_whatsapp));
```

---

## 📊 Database Schema

Tabel `users` sudah memiliki kolom:

```sql
- social_id VARCHAR(255) UNIQUE -- ID dari social provider
- social_provider VARCHAR(255) -- Provider name (facebook, google, etc)
- whatsapp VARCHAR(20) -- Nomor WhatsApp admin
- profile_photo VARCHAR(255) -- Foto profil dari social media
- nama_bank VARCHAR(100) -- Nama bank untuk pembayaran
- nomor_rekening VARCHAR(30) -- Nomor rekening
- nama_pemilik_rekening VARCHAR(255) -- Nama pemilik rekening
```

---

## 🔐 Security Checklist

- ✅ Facebook App Secret tidak pernah di-hardcode
- ✅ WhatsApp number di-validate dengan regex
- ✅ Social ID unique di database (prevent duplicate accounts)
- ✅ Password social login di-randomize (Str::random(16))
- ✅ HTTPS untuk production (dalam FACEBOOK_REDIRECT_URI)
- ✅ CSRF protection enabled (form sudah ada @csrf)

---

## 🐛 Common Issues & Solutions

### **Issue 1: "SQLSTATE[42S21]: Column not found"**
```
Solusi:
php artisan migrate --step
php artisan migrate
```

### **Issue 2: Facebook Login Blank Page**
```
Solusi:
1. Pastikan FACEBOOK_REDIRECT_URI di .env sesuai FB App Settings
2. php artisan cache:clear
3. php artisan config:clear
```

### **Issue 3: WhatsApp Field Tidak Simpan**
```
Solusi:
1. Check validation error di blade: @error('whatsapp')
2. Pastikan format: +62xxx atau 08xxx
3. Debug: php artisan tinker → $user->whatsapp;
```

### **Issue 4: Helper Not Found**
```
Solusi:
1. Pastikan file ada: app/Helpers/AdminHelper.php
2. Run: composer dump-autoload
3. Restart: php artisan cache:clear
```

---

## 📱 WhatsApp Link Format

Format nomor untuk WhatsApp API:

```
Input: +6281234567890
Formatted: 6281234567890
Link: https://wa.me/6281234567890

Input: 081234567890
Formatted: 6281234567890
Link: https://wa.me/6281234567890

Input: 08-1234-567890
Formatted: 6281234567890
Link: https://wa.me/6281234567890
```

---

## 🎨 UI Components Ready to Use

### **Floating WhatsApp Button**
```blade
@if(AdminHelper::hasAdminWhatsApp())
    <a href="{{ AdminHelper::getAdminWhatsAppLink() }}" 
       target="_blank" 
       class="btn btn-success rounded-circle floating-button">
        <i class="bi bi-whatsapp"></i>
    </a>
@endif
```

### **Contact Card**
```blade
<div class="card border-success">
    <div class="card-header bg-success text-white">
        <h5>Hubungi Admin</h5>
    </div>
    <div class="card-body text-center">
        @if($adminContactInfo['whatsapp_link'])
            <a href="{{ $adminContactInfo['whatsapp_link'] }}" 
               class="btn btn-success btn-lg d-block">
                Chat via WhatsApp
            </a>
        @endif
    </div>
</div>
```

### **Footer Contact**
```blade
<footer class="bg-dark text-white">
    <div class="container">
        <h5>Kontak Kami</h5>
        <a href="{{ AdminHelper::getAdminWhatsAppLink() }}" 
           target="_blank" class="d-block mb-2">
            <i class="bi bi-whatsapp"></i> 
            {{ AdminHelper::getAdminWhatsApp() }}
        </a>
    </div>
</footer>
```

---

## 📚 Referensi File

| File | Purpose | Status |
|------|---------|--------|
| `app/Helpers/AdminHelper.php` | Helper untuk WhatsApp | ✅ BARU |
| `app/Http/Controllers/Api/AdminContactApiController.php` | API Endpoints | ✅ BARU |
| `resources/views/examples/admin_helper_examples.blade.php` | Contoh Penggunaan | ✅ BARU |
| `SETUP_FACEBOOK_WHATSAPP.md` | Setup Guide | ✅ BARU |
| `ProfileController.php` | Save WhatsApp | ✅ UPDATE |
| `AppServiceProvider.php` | Share AdminHelper | ✅ UPDATE |
| `routes/web.php` | API Routes | ✅ UPDATE |

---

## 🚀 Siap untuk Production?

Checklist sebelum deploy:

- [ ] Facebook App di production mode (bukan development)
- [ ] HTTPS enabled di production
- [ ] `.env` sudah di-set dengan credentials production
- [ ] Database sudah di-migrate di production
- [ ] Cache & config di-clear: `php artisan cache:clear`
- [ ] Test semua fitur di production environment

---

## 📞 Support & Next Steps

### Untuk bantuan lebih lanjut:
1. Cek file `SETUP_FACEBOOK_WHATSAPP.md` untuk troubleshooting
2. Lihat file examples di `resources/views/examples/admin_helper_examples.blade.php`
3. Test API endpoints di `http://localhost:8000/api/admin/...`

### Fitur yang bisa ditambahkan:
1. **Google OAuth** - Tambah social login dengan Google
2. **WhatsApp Notifications** - Kirim notifikasi otomatis via WhatsApp API
3. **Multi-Admin** - Support multiple admin dengan WhatsApp berbeda
4. **SMS Integration** - Tambah SMS sebagai backup dari WhatsApp
5. **Admin Approval** - Admin perlu approve pesanan via WhatsApp

---

**Last Updated**: April 27, 2026  
**Maintained By**: Development Team  
**License**: MIT
