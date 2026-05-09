# Setup Facebook OAuth & WhatsApp Integration

## 📋 Daftar Isi
1. [Facebook OAuth Setup](#facebook-oauth-setup)
2. [WhatsApp Admin Integration](#whatsapp-admin-integration)
3. [Testing & Verifikasi](#testing--verifikasi)
4. [Troubleshooting](#troubleshooting)

---

## 🔵 Facebook OAuth Setup

### Step 1: Buat Facebook App
1. Kunjungi [Facebook Developers Console](https://developers.facebook.com/)
2. Login dengan akun Facebook Anda
3. Klik **My Apps** → **Create App**
4. Pilih **Consumer** sebagai app type
5. Isi form dengan informasi aplikasi Anda:
   - **App Name**: Smolie Gift
   - **App Contact Email**: your-email@example.com
   - Klik **Create App**

### Step 2: Setup Facebook Login Product
1. Di dashboard app Anda, cari **Products** section
2. Klik **+ Add Product**
3. Cari **Facebook Login**, klik **Set Up**
4. Pilih **Web** sebagai platform

### Step 3: Konfigurasi OAuth Redirect URLs
1. Buka **Settings → Basic** untuk mendapatkan:
   - ✅ **App ID** (Simpan)
   - ✅ **App Secret** (Simpan & Jangan Bagikan)

2. Buka **Products → Facebook Login → Settings**
3. Di bagian **Valid OAuth Redirect URIs**, tambahkan:
   ```
   http://localhost/auth/facebook/callback
   http://yourdomain.com/auth/facebook/callback
   http://www.yourdomain.com/auth/facebook/callback
   ```
4. Klik **Save Changes**

### Step 4: Update `.env` File
```env
# .env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

### Step 5: Jalankan Migration
```bash
php artisan migrate
```

---

## 📱 WhatsApp Admin Integration

### Fitur:
- Admin dapat mengubah nomor WhatsApp di **Edit Profil**
- Nomor WhatsApp akan ditampilkan di **Dashboard** untuk pembeli
- Format nomor: `+62xxx` atau `08xxx`

### Implementasi:

#### 1. **Admin mengubah WhatsApp:**
- Buka **Edit Profil** (User Profile > Edit)
- Masukkan Nomor WhatsApp dengan format: `+62812345678` atau `08812345678`
- Simpan perubahan

#### 2. **WhatsApp ditampilkan di Dashboard:**
Gunakan kode berikut untuk menampilkan WhatsApp admin:

```blade
<!-- Di file blade manapun -->
@php
    $admin = App\Models\User::where('usertype', 'admin')->first();
@endphp

@if($admin?->whatsapp)
    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $admin->whatsapp) }}" 
       target="_blank" class="btn btn-success">
        <i class="bi bi-whatsapp"></i> Chat Admin di WhatsApp
    </a>
@endif
```

#### 3. **Di HomeController untuk menampilkan ke pembeli:**
```php
public function index()
{
    $admin = User::where('usertype', 'admin')->first();
    
    return view('home', [
        'adminWhatsApp' => $admin?->whatsapp,
    ]);
}
```

#### 4. **Di Blade View (home.blade.php):**
```blade
@if($adminWhatsApp)
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-whatsapp text-success"></i> Hubungi Admin
            </h6>
            <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $adminWhatsApp) }}" 
               target="_blank" class="btn btn-sm btn-success w-100">
                <i class="bi bi-whatsapp me-1"></i> Chat Admin
            </a>
        </div>
    </div>
@endif
```

---

## ✅ Testing & Verifikasi

### Test Facebook Login:
1. Buka halaman login: `http://localhost/login`
2. Klik tombol **ATAU MASUK DENGAN Facebook**
3. Seharusnya redirect ke Facebook login
4. Setelah login sukses, redirect kembali ke home

### Test WhatsApp Update:
1. Login sebagai Admin
2. Klik **Profile → Edit Profil**
3. Isi nomor WhatsApp: `+6281234567890` atau `081234567890`
4. Klik **Simpan**
5. Verifikasi nomor tersimpan di database:
   ```bash
   php artisan tinker
   >>> $user = User::where('usertype', 'admin')->first();
   >>> $user->whatsapp;
   ```

---

## 🐛 Troubleshooting

### 1. **Facebook Login Button Tidak Muncul**
**Penyebab:** File blade tidak ter-load dengan benar
**Solusi:**
```bash
php artisan view:clear
php artisan cache:clear
```

### 2. **"Redirect URI Mismatch" Error**
**Penyebab:** URL redirect tidak sesuai di Facebook App Settings
**Solusi:**
- Pastikan domain di `.env` FACEBOOK_REDIRECT_URI sesuai dengan setting di Facebook
- Jangan lupa tambahkan `http://` atau `https://`

### 3. **Social Login Berhasil tapi Halaman Blank**
**Penyebab:** 
- Belum run migration
- User tidak ter-create dengan benar
**Solusi:**
```bash
# 1. Run migration
php artisan migrate

# 2. Check user di database
php artisan tinker
>>> User::where('social_provider', 'facebook')->get();

# 3. Check error log
tail -f storage/logs/laravel.log
```

### 4. **WhatsApp Field Tidak Tersimpan**
**Penyebab:** 
- Migration belum dijalankan
- FormRequest validation error
**Solusi:**
```bash
# 1. Check if column exists
php artisan tinker
>>> Schema::hasColumn('users', 'whatsapp');

# 2. Jika tidak ada, jalankan migration
php artisan migrate

# 3. Lihat validation error di blade:
@error('whatsapp')
    <div class="text-danger">{{ $message }}</div>
@enderror
```

---

## 📊 Database Schema

Field yang ditambahkan:
```sql
ALTER TABLE users ADD COLUMN social_id VARCHAR(255) UNIQUE NULLABLE;
ALTER TABLE users ADD COLUMN social_provider VARCHAR(255) NULLABLE;
ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) NULLABLE;
ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) NULLABLE;
ALTER TABLE users ADD COLUMN nama_bank VARCHAR(100) NULLABLE;
ALTER TABLE users ADD COLUMN nomor_rekening VARCHAR(30) NULLABLE;
ALTER TABLE users ADD COLUMN nama_pemilik_rekening VARCHAR(255) NULLABLE;
```

---

## 🔒 Security Tips

1. **Jangan hardcode credentials di code:**
   - Selalu gunakan `.env` untuk Facebook App Secret

2. **Validate WhatsApp Format:**
   - Sudah diimplementasikan: `regex:/^(\+62|0)[0-9]{9,12}$/`

3. **Secure Redirect:**
   - Gunakan `route('intended')` untuk redirect setelah login
   - Cegah open redirect vulnerability

4. **HTTPS untuk Production:**
   ```env
   FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
   ```

---

## 📝 Ringkasan Perubahan

✅ **FacebookAuthController.php** - Sudah ada, siap pakai
✅ **ProfileController.php** - Updated untuk save WhatsApp
✅ **profile/edit.blade.php** - Sudah ada form WhatsApp
✅ **Migrations** - Sudah ada di database/migrations
✅ **config/services.php** - Sudah ada konfigurasi Facebook
✅ **routes/web.php** - Sudah ada routes untuk Facebook OAuth

---

## 🎯 Next Steps (Opsional)

Untuk enhancement lebih lanjut:
1. **Google Login** - Tambah provider Socialite (Google)
2. **WhatsApp API** - Kirim notifikasi otomatis via WhatsApp
3. **Multi-Language** - Support bahasa lain
4. **Rate Limiting** - Proteksi dari spam login

---

**Updated**: April 27, 2026
**Version**: 1.0
