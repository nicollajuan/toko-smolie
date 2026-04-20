# QUICK START GUIDE - 4 Fitur Baru Toko Smolie

## Ringkas Setup

### 1. JALANKAN MIGRATION
```bash
php artisan migrate
```

Ini akan membuat kolom baru di tabel `users` dan `transaksi`:
- `users`: social_id, social_provider, whatsapp, profile_photo
- `transaksi`: status_pembayaran, bukti_pembayaran, qris_data

---

### 2. SETUP FACEBOOK LOGIN

#### Step A: Buat Facebook App
1. Kunjungi https://developers.facebook.com/
2. Klik "My Apps" → "Create App"
3. Pilih "Consumer" sebagai app type
4. Isi "App Name" (misal: Smolie Gift Dev)
5. Copy **App ID** dan **App Secret**

#### Step B: Configure Facebook App
1. Di panel Facebook App, buka "Settings" → "Basic"
2. Catat **App ID** dan **App Secret**
3. Buka "Facebook Login" → "Settings"
4. Isi "Valid OAuth Redirect URIs":
   ```
   http://localhost/auth/facebook/callback
   http://yourdomain.com/auth/facebook/callback
   ```

#### Step C: Update .env File
```env
FACEBOOK_CLIENT_ID=paste_app_id_here
FACEBOOK_CLIENT_SECRET=paste_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

#### Step D: Test
- Buka halaman login: http://localhost/login
- Klik tombol "Facebook"
- Jika berhasil, user akan auto-login & create akun

---

### 3. QRIS PAYMENT SUDAH SIAP

**Tidak perlu setup tambahan!** QRIS sudah built-in dengan QR code generation.

#### Test QRIS Flow:
1. User checkout dengan metode "QRIS"
2. System redirect ke halaman pembayaran: `/pembayaran/{id}`
3. Tampil QR code QRIS
4. User bisa langsung upload bukti pembayaran

#### Untuk Production QRIS (Dynamic):
Jika ingin generate QRIS dinamis berdasarkan nominal, install:
```bash
composer require verifone/laravel-qris
```

Kemudian modify method `generateQrisUrl()` di `PaymentController`.

---

### 4. WHATSAPP ADMIN FIELD

**Auto-enabled** untuk user dengan `usertype = 'admin'`

#### Cara Setup:
1. Admin masuk ke profil: `/profile/edit`
2. Scroll ke section "Nomor WhatsApp"
3. Isi nomor dengan format: `+62812345678`
4. Klik "Simpan Perubahan"

#### Hasil:
- Di halaman transaksi, tombol WhatsApp akan muncul
- User bisa langsung chat admin lewat WhatsApp

---

### 5. UPLOAD BUKTI PEMBAYARAN

**Automatic** setelah user klik "Upload" di halaman pembayaran

#### File Requirements:
- Format: JPG, PNG, PDF
- Ukuran: Max 2MB
- Required: Ya

#### Lokasi Penyimpanan:
```
storage/app/public/bukti_pembayaran/TRX-{id}-{timestamp}.{extension}
```

#### Admin Verification:
1. Buka halaman "Riwayat Transaksi"
2. Klik tombol "Lihat Bukti" di transaksi QRIS
3. Preview bukti di modal
4. Klik "Setujui" atau "Tolak"
5. Status pembayaran akan update

---

## File Upload Directory Setup

Pastikan directory sudah linked:

```bash
php artisan storage:link
```

Ini akan create symlink dari:
```
storage/app/public/ → public/storage/
```

**Check**: Buka http://localhost/storage/ - harus accessible

---

## Testing Checklist

- [ ] Facebook Login: Masuk lewat Facebook
- [ ] Auto User Create: User baru auto-created jika belum ada
- [ ] Profile Photo: Foto dari Facebook tersimpan
- [ ] QRIS Payment: Klik "QRIS" saat checkout
- [ ] QR Code: QR code muncul di halaman pembayaran
- [ ] Upload Proof: Upload bukti pembayaran (JPG/PNG/PDF)
- [ ] Admin Verify: Admin lihat & verifikasi bukti
- [ ] WhatsApp Button: Tombol WhatsApp muncul di transaksi
- [ ] Admin WhatsApp: Nomor WhatsApp bisa diedit di profil

---

## Error Troubleshooting

### "Facebook Login Fails"
**Solusi**:
- Check .env variables (FACEBOOK_CLIENT_ID, FACEBOOK_CLIENT_SECRET)
- Check FACEBOOK_REDIRECT_URI match di Facebook App settings
- Clear cache: `php artisan config:clear`

### "Storage Link Error"
**Solusi**:
```bash
rm public/storage
php artisan storage:link
```

### "Upload File Fails"
**Solusi**:
- Check folder permissions: `chmod -R 755 storage/app/public/`
- Check PHP upload size: `php.ini` → `upload_max_filesize = 50M`
- Restart server

### "QRIS QR Code Blank"
**Solusi**:
- Package sudah installed: `composer show | grep qr-code`
- Check controller `generateQrCode()` tidak error
- Check browser console untuk error

### "WhatsApp Field Not Showing"
**Solusi**:
- Field hanya muncul untuk `usertype = 'admin'`
- Update user type di database:
  ```sql
  UPDATE users SET usertype = 'admin' WHERE id = 1;
  ```

---

## Important Security Notes

1. **File Upload Validation**: File type & size sudah divalidasi
2. **Authorization Check**: Hanya owner yang bisa upload, hanya admin yang bisa verify
3. **Social Login**: Password tidak di-store untuk social auth users
4. **WhatsApp**: Nomor disimpan tapi tidak di-share public

---

## Command Reference

```bash
# Setup
php artisan migrate                          # Run migrations
php artisan storage:link                     # Link storage
php artisan config:clear                     # Clear cache

# Development
php artisan serve                            # Start dev server
php artisan tinker                           # PHP shell

# Maintenance
php artisan migrate:rollback                 # Revert migrations
php artisan migrate:refresh                  # Fresh migrate (DELETE DATA!)
php artisan tinker                           # Check data:
#   User::find(1)->social_id                # Check Facebook ID
#   Transaksi::find(1)->bukti_pembayaran    # Check proof file
```

---

## Useful Links

- Facebook Developers: https://developers.facebook.com/
- Laravel Socialite Docs: https://laravel.com/docs/socialite
- QR Code Package: https://github.com/endroid/qr-code

---

**Questions? Check:** `FITUR_BARU_DOCUMENTATION.md` for detailed technical documentation.
