# Dokumentasi Implementasi 4 Fitur Baru Toko Smolie

## Overview
Dokumen ini menjelaskan implementasi 4 fitur baru yang telah ditambahkan ke sistem Toko Smolie:
1. Login menggunakan Facebook
2. Pembayaran QRIS
3. Nomor WhatsApp Admin
4. Upload Bukti Pembayaran

---

## 1. LOGIN MENGGUNAKAN FACEBOOK

### Deskripsi
User dapat login menggunakan akun Facebook mereka. Jika user belum terdaftar, sistem akan membuat akun baru otomatis dengan data dari Facebook.

### File yang Ditambahkan/Dimodifikasi

#### a. Controller: `FacebookAuthController.php`
**Lokasi**: `app/Http/Controllers/FacebookAuthController.php`

**Fungsi Utama**:
- `redirect()`: Redirect ke halaman login Facebook
- `callback()`: Handle callback dari Facebook setelah login
- `downloadProfilePhoto()`: Download foto profil dari Facebook
- `generateUsername()`: Generate username unik dari nama

**Logika**:
```
1. User klik tombol "Login Facebook"
2. Redirect ke halaman login Facebook (Socialite)
3. Facebook return user data
4. Cari user di database berdasarkan social_id
   - Jika ada: Login langsung
   - Jika tidak ada:
     - Cek email sudah ada?
       - Jika ada: Update dengan social_id
       - Jika belum: Buat user baru dengan role 'pembeli'
5. Download foto profil dan simpan ke storage
6. Auto-login user
```

#### b. Model: `User.php`
**Modifikasi**: Tambah field ke `$fillable`:
```php
'social_id',        // ID dari Facebook
'social_provider',  // 'facebook'
'whatsapp',         // Nomor WhatsApp admin
'profile_photo',    // Foto dari social media
```

#### c. View: `resources/views/auth/login.blade.php`
**Modifikasi**: Ubah tombol Facebook dari button menjadi link ke route:
```php
<a href="{{ route('facebook.redirect') }}" class="btn-social shadow-sm">
    <i class="bi bi-facebook text-primary me-2"></i> Facebook
</a>
```

#### d. Routes: `routes/web.php`
**Tambah Route**:
```php
Route::get('/auth/facebook', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');
```

#### e. Config: `config/services.php`
**Tambah Config**:
```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

### Cara Setup Facebook OAuth

1. **Buat Facebook App** di https://developers.facebook.com/
   - Pilih "Consumers" sebagai app type
   - Konfigurasi Facebook Login

2. **Dapatkan Credentials**:
   - App ID → `FACEBOOK_CLIENT_ID`
   - App Secret → `FACEBOOK_CLIENT_SECRET`

3. **Set `.env`**:
```env
FACEBOOK_CLIENT_ID=YOUR_APP_ID
FACEBOOK_CLIENT_SECRET=YOUR_APP_SECRET
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

4. **Jalankan Migration**:
```bash
php artisan migrate
```

---

## 2. PEMBAYARAN QRIS

### Deskripsi
User dapat melakukan pembayaran menggunakan QRIS. Sistem akan generate QR code berdasarkan nominal pembayaran.

### File yang Ditambahkan/Dimodifikasi

#### a. Controller: `PaymentController.php`
**Lokasi**: `app/Http/Controllers/PaymentController.php`

**Fungsi Utama**:
- `show($id)`: Tampilkan halaman pembayaran dengan QRIS
- `generateQrisUrl()`: Generate QRIS URL/string
- `generateQrCode()`: Generate QR code image
- `uploadProof()`: Handle upload bukti pembayaran
- `verifyPayment()`: Admin verifikasi pembayaran
- `downloadProof()`: Download bukti pembayaran

**Parameter QRIS**:
- Saat ini menggunakan format static untuk demo
- Dalam production, gunakan library `php-qris` untuk generate dynamic QRIS

#### b. Migration: `2026_04_19_000001_add_payment_fields_to_transaksi_table.php`
**Tambah Field ke Tabel `transaksi`**:
```php
$table->enum('status_pembayaran', ['pending', 'berhasil', 'gagal'])->default('pending');
$table->string('bukti_pembayaran')->nullable();
$table->text('qris_data')->nullable();
```

#### c. View: `resources/views/pembeli/payment.blade.php`
**Tampil**:
- QR Code QRIS
- Rincian transaksi
- Form upload bukti pembayaran
- Status pembayaran

#### d. Routes: `routes/web.php`
**Tambah Routes**:
```php
// User lihat & upload bukti
Route::get('/pembayaran/{id}', [PaymentController::class, 'show'])->name('pembayaran.show');
Route::post('/pembayaran/{id}/upload-proof', [PaymentController::class, 'uploadProof'])->name('pembayaran.uploadProof');
Route::get('/pembayaran/{id}/download-proof', [PaymentController::class, 'downloadProof'])->name('pembayaran.downloadProof');

// Admin verifikasi
Route::post('/admin/pembayaran/{id}/verify', [PaymentController::class, 'verifyPayment'])->name('pembayaran.verify');
```

#### e. Flow Pembayaran
```
1. User checkout → Redirect ke halaman pembayaran
2. Tampilkan QR Code QRIS
3. User scan & bayar via banking app
4. User upload bukti pembayaran (JPG/PNG/PDF max 2MB)
5. Sistem simpan ke storage/bukti_pembayaran/
6. Admin verifikasi bukti
7. Status pembayaran berubah menjadi "berhasil" atau "gagal"
```

### Implementasi Production QRIS

Untuk QRIS yang real (bukan static), gunakan library:

```bash
composer require verifone/laravel-qris
```

Kemudian modify `generateQrisUrl()` di PaymentController:

```php
private function generateQrisUrl(Transaksi $transaksi)
{
    $qris = new QrisGenerator();
    return $qris->amount($transaksi->total_harga)
                ->merchantName('Smolie Gift')
                ->merchantCity('Jakarta')
                ->generate();
}
```

---

## 3. NOMOR WHATSAPP ADMIN

### Deskripsi
Admin dapat menyimpan nomor WhatsApp mereka di profil. Tombol WhatsApp akan muncul di halaman transaksi.

### File yang Ditambahkan/Dimodifikasi

#### a. Migration: `2026_04_19_000000_add_social_and_whatsapp_to_users_table.php`
**Tambah Field ke Tabel `users`**:
```php
$table->string('whatsapp')->nullable();
```

#### b. Model: `User.php`
**Modifikasi `$fillable`**:
```php
'whatsapp', // Nomor WhatsApp
```

#### c. View: `resources/views/profile/edit.blade.php`
**Tambah Field Input**:
```php
@if(auth()->user()->usertype === 'admin')
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-bold small text-uppercase text-muted">Nomor WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-whatsapp text-success"></i>
                </span>
                <input type="tel" name="whatsapp" 
                       value="{{ old('whatsapp', $user->whatsapp) }}"
                       placeholder="+62812345678">
            </div>
            <small class="text-muted">Format: +62...</small>
        </div>
    </div>
@endif
```

#### d. ProfileController
Pastikan ada validasi & update di method `update()`:

```php
$request->validate([
    'whatsapp' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
]);

auth()->user()->update([
    'whatsapp' => $request->whatsapp,
]);
```

---

## 4. UPLOAD BUKTI PEMBAYARAN

### Deskripsi
User dapat upload bukti pembayaran mereka. Admin dapat melihat, download, dan verifikasi bukti tersebut.

### File yang Ditambahkan/Dimodifikasi

#### a. Storage Configuration
**File**: `config/filesystems.php`

Pastikan config `public` sudah benar:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

Jalankan command:
```bash
php artisan storage:link
```

#### b. Controller: `PaymentController.php`
**Fungsi**: `uploadProof()`

**Validasi**:
- Format: JPG, PNG, PDF
- Ukuran: Max 2MB
- Required

**Penyimpanan**:
```php
$file = $request->file('bukti_pembayaran');
$filename = 'TRX-' . $transaksi->id . '-' . time() . '.' . $file->getClientOriginalExtension();

$path = Storage::disk('public')->putFileAs(
    'bukti_pembayaran',
    $file,
    $filename
);

$transaksi->update([
    'bukti_pembayaran' => $filename,
    'status_pembayaran' => 'pending', // Menunggu verifikasi
]);
```

**Lokasi File**: `storage/app/public/bukti_pembayaran/`

#### c. View: `resources/views/pembeli/payment.blade.php`
**Upload Section**:
- Input file dengan validasi client-side
- Preview file yang sudah diupload
- Status pembayaran

#### d. View: `resources/views/transaksi/index.blade.php`
**Admin Section**:
- Tombol lihat bukti pembayaran
- Modal preview
- Tombol verifikasi (Setujui/Tolak)
- Download bukti

#### e. Routes
```php
Route::post('/pembayaran/{id}/upload-proof', 
    [PaymentController::class, 'uploadProof'])->name('pembayaran.uploadProof');
Route::get('/pembayaran/{id}/download-proof', 
    [PaymentController::class, 'downloadProof'])->name('pembayaran.downloadProof');
Route::post('/admin/pembayaran/{id}/verify', 
    [PaymentController::class, 'verifyPayment'])->name('pembayaran.verify');
```

### Flow Upload & Verifikasi

```
1. User upload bukti di halaman pembayaran
   - File disimpan ke storage/bukti_pembayaran/
   - Database update with filename & status='pending'

2. Admin lihat bukti di daftar transaksi
   - Klik tombol lihat bukti → Modal preview
   - Bisa download atau verifikasi

3. Admin klik "Setujui" atau "Tolak"
   - Status berubah menjadi 'berhasil' atau 'gagal'
   - User dapat lihat status di halaman pembayaran
```

---

## Summary Perubahan Database

### Tabel `users`
**Tambah Kolom**:
```sql
ALTER TABLE users ADD COLUMN social_id VARCHAR(255) UNIQUE NULLABLE;
ALTER TABLE users ADD COLUMN social_provider VARCHAR(255) NULLABLE;
ALTER TABLE users ADD COLUMN whatsapp VARCHAR(20) NULLABLE;
ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) NULLABLE;
```

### Tabel `transaksi`
**Tambah Kolom**:
```sql
ALTER TABLE transaksi ADD COLUMN status_pembayaran ENUM('pending','berhasil','gagal') DEFAULT 'pending';
ALTER TABLE transaksi ADD COLUMN bukti_pembayaran VARCHAR(255) NULLABLE;
ALTER TABLE transaksi ADD COLUMN qris_data LONGTEXT NULLABLE;
```

---

## Package Dependencies

Tambahan package yang sudah diinstall:

1. **Laravel Socialite**: `laravel/socialite`
   - Untuk Facebook OAuth login

2. **Endroid QR Code**: `endroid/qr-code`
   - Untuk generate QR code QRIS

---

## Checklist Implementasi

- [x] Install Laravel Socialite
- [x] Install Endroid QR Code
- [x] Create migrations
- [x] Update User model
- [x] Update Transaksi model
- [x] Create DetailTransaksi model
- [x] Create FacebookAuthController
- [x] Create PaymentController
- [x] Create payment.blade.php
- [x] Update login.blade.php
- [x] Update profile/edit.blade.php
- [x] Update transaksi/index.blade.php
- [x] Add routes di web.php
- [x] Add config di services.php

---

## Troubleshooting

### Facebook Login Gagal
- Pastikan FACEBOOK_* env sudah set
- Redirect URI harus match dengan yang di Facebook App
- Check Laravel log di `storage/logs/`

### QRIS QR Code Tidak Muncul
- Pastikan endroid/qr-code sudah terinstall: `composer show | grep qr`
- Check permission folder `resources/` dan `storage/`

### Upload Bukti Gagal
- Pastikan `storage:link` sudah dijalankan
- Check permission folder `storage/app/public/`
- Pastikan max upload size di php.ini (upload_max_filesize)

### WhatsApp Button Tidak Muncul di Admin
- Hanya muncul jika `usertype === 'admin'`
- Pastikan user sudah punya akses admin di database

---

## Testing

### Test Facebook Login
```bash
# Set env test
FACEBOOK_CLIENT_ID=test_id
FACEBOOK_CLIENT_SECRET=test_secret

# Login lewat http://localhost/auth/facebook
```

### Test QRIS Payment
```bash
# Buat transaksi dengan metode 'qris'
# Kunjungi /pembayaran/{transaksi_id}
# Scan QR code (di dev: gunakan test QRIS)
```

### Test Upload Bukti
```bash
# Upload file JPG/PNG/PDF di halaman pembayaran
# Check storage/app/public/bukti_pembayaran/
# Admin verify di halaman transaksi
```

---

## Catatan Penting

1. **Security**:
   - Validasi file upload ketat (type, size)
   - Hanya owner transaksi yang bisa upload bukti
   - Hanya admin yang bisa verifikasi

2. **Production**:
   - Setup QRIS real (bukan static)
   - Setup email notification untuk upload bukti
   - Setup 2FA untuk admin verification

3. **Performance**:
   - Compress foto dari Facebook sebelum simpan
   - Optimize QRIS QR code generation
   - Consider caching untuk list transaksi

---

**Last Updated**: 2026-04-19
**Version**: 1.0
