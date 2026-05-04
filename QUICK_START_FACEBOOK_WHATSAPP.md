# 🚀 QUICK START GUIDE
## Facebook Login + WhatsApp Integration

Panduan singkat untuk setup dalam 5 menit!

---

## ✅ Step 1: Setup Environment (.env)

```bash
# Edit .env dan tambahkan:
FACEBOOK_CLIENT_ID=your_app_id_from_facebook_developers
FACEBOOK_CLIENT_SECRET=your_app_secret_from_facebook_developers
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

**Bagaimana mendapat credentials?**
1. Kunjungi [Facebook Developers](https://developers.facebook.com/)
2. Login → My Apps → Create App
3. Setup Facebook Login product
4. Copy App ID & App Secret ke .env Anda

---

## ✅ Step 2: Database Migration

```bash
php artisan migrate
```

Ini akan membuat kolom: `social_id`, `social_provider`, `whatsapp`

---

## ✅ Step 3: Test Facebook Login

```bash
php artisan serve
# Buka: http://localhost:8000/login
# Klik tombol "ATAU MASUK DENGAN Facebook"
```

**Expected Result:**
- Redirect ke Facebook login
- Setelah approve, auto-login & redirect ke home
- User baru tercreate di database dengan social_id

---

## ✅ Step 4: Setup Admin WhatsApp

1. **Login sebagai Admin**
2. **Klik Profile → Edit Profil**
3. **Isi Nomor WhatsApp**: `+6281234567890` atau `081234567890`
4. **Klik Simpan**

✅ WhatsApp sudah tersimpan! Sekarang bisa ditampilkan di website.

---

## ✅ Step 5: Tampilkan WhatsApp di Website

### Cara 1: Simple (Paling Mudah)
```blade
<!-- Di file blade manapun -->
@if($adminContactInfo['whatsapp_link'])
    <a href="{{ $adminContactInfo['whatsapp_link'] }}" target="_blank">
        <i class="bi bi-whatsapp"></i> Chat Admin
    </a>
@endif
```

### Cara 2: Menggunakan Helper
```blade
@php
    $link = \App\Helpers\AdminHelper::getAdminWhatsAppLink('Halo Admin!');
@endphp

@if($link)
    <a href="{{ $link }}" target="_blank" class="btn btn-success">
        Chat via WhatsApp
    </a>
@endif
```

### Cara 3: Floating Button (Best UX)
```blade
@if(\App\Helpers\AdminHelper::hasAdminWhatsApp())
    <a href="{{ \App\Helpers\AdminHelper::getAdminWhatsAppLink() }}" 
       target="_blank" 
       class="btn btn-success rounded-circle" 
       style="position: fixed; bottom: 20px; right: 20px; z-index: 999; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
    </a>
@endif
```

---

## 🔍 Verify Setup

### Test di Terminal:
```bash
php artisan tinker

# Get admin WhatsApp
>>> $admin = App\Models\User::where('usertype', 'admin')->first();
>>> $admin->whatsapp;
// Output: +6281234567890

# Get formatted WhatsApp link
>>> App\Helpers\AdminHelper::getAdminWhatsAppLink();
// Output: https://wa.me/6281234567890

# Check if has WhatsApp
>>> App\Helpers\AdminHelper::hasAdminWhatsApp();
// Output: true
```

### Test di Browser:
```
GET /api/admin/whatsapp
GET /api/admin/contact-info
GET /api/admin/has-whatsapp
```

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Facebook button tidak muncul | `php artisan view:clear` |
| Redirect URI Mismatch | Pastikan FACEBOOK_REDIRECT_URI di .env sesuai FB Settings |
| WhatsApp field tidak tersimpan | Cek format: harus `+62xxx` atau `08xxx` |
| Helper not found | `composer dump-autoload` |
| Column not found error | `php artisan migrate` |

---

## 📱 API Endpoints

```javascript
// Get semua info admin
fetch('/api/admin/contact-info')
  .then(r => r.json())
  .then(d => console.log(d.data))

// Get WhatsApp only
fetch('/api/admin/whatsapp')
  .then(r => r.json())
  .then(d => console.log(d.whatsapp_link))

// Check ada WhatsApp atau tidak
fetch('/api/admin/has-whatsapp')
  .then(r => r.json())
  .then(d => console.log(d.has_whatsapp))
```

---

## 📚 Full Documentation

- **Setup Lengkap**: Baca `SETUP_FACEBOOK_WHATSAPP.md`
- **Implementasi Lanjut**: Baca `IMPLEMENTATION_GUIDE.md`
- **Contoh Kode**: Lihat `resources/views/examples/admin_helper_examples.blade.php`

---

## ✨ That's It! 

Anda sudah setup:
- ✅ Facebook OAuth Login
- ✅ WhatsApp Admin Integration
- ✅ API Endpoints

Sekarang tinggal tampilkan di UI dan enjoy! 🎉

---

**Version**: 1.0  
**Last Updated**: April 27, 2026
