# Code Examples - 4 Fitur Baru

## 1. FACEBOOK LOGIN

### Model: User.php
```php
// Add to $fillable array
protected $fillable = [
    'name',
    'email',
    'password',
    'usertype',
    'username',
    'jenis_kelamin',
    'alamat',
    'no_hp',
    'foto',
    'social_id',              // NEW
    'social_provider',        // NEW
    'whatsapp',               // NEW
    'profile_photo',          // NEW
];

// Add method untuk check social login
public function isSocialLogin(): bool
{
    return !is_null($this->social_id) && !is_null($this->social_provider);
}
```

### Controller: FacebookAuthController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class FacebookAuthController extends Controller
{
    // Redirect ke Facebook
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle callback dari Facebook
    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->fields(['name', 'email', 'picture.width(300).height(300)'])
                ->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook login gagal');
        }

        // Cari user
        $user = User::where('social_id', $facebookUser->id)
                    ->where('social_provider', 'facebook')
                    ->first();

        // User tidak ada - cek email atau buat baru
        if (!$user) {
            $existingUser = User::where('email', $facebookUser->email)->first();

            if ($existingUser) {
                $existingUser->update([
                    'social_id' => $facebookUser->id,
                    'social_provider' => 'facebook',
                    'profile_photo' => $this->downloadProfilePhoto($facebookUser),
                ]);
                $user = $existingUser;
            } else {
                $user = User::create([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'social_id' => $facebookUser->id,
                    'social_provider' => 'facebook',
                    'password' => bcrypt(Str::random(16)),
                    'usertype' => 'pembeli',
                    'username' => $this->generateUsername($facebookUser->name),
                    'profile_photo' => $this->downloadProfilePhoto($facebookUser),
                ]);
            }
        }

        // Login user
        Auth::login($user, remember: true);

        return redirect()->intended('/');
    }

    private function downloadProfilePhoto($facebookUser)
    {
        try {
            $photoUrl = $facebookUser->getAvatar();
            if (!$photoUrl) return null;

            $filename = 'profile_' . $facebookUser->id . '_' . time() . '.jpg';
            $path = 'img/user/' . $filename;

            $photo = file_get_contents($photoUrl);

            if (!file_exists(public_path('img/user'))) {
                mkdir(public_path('img/user'), 0755, true);
            }

            file_put_contents(public_path($path), $photo);

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateUsername($name)
    {
        $baseUsername = Str::slug(Str::lower($name), '');
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
```

### Routes: web.php
```php
Route::get('/auth/facebook', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');
```

### Config: services.php
```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

---

## 2. QRIS PAYMENT

### Controller: PaymentController.php (Key Methods)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Tampilkan halaman pembayaran
    public function show($id)
    {
        $transaksi = Transaksi::with('user')->findOrFail($id);

        // Authorization
        if (Auth::user()->id !== $transaksi->user_id && Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $qrisUrl = $this->generateQrisUrl($transaksi);
        $qrCodeImage = $this->generateQrCode($qrisUrl);

        return view('pembeli.payment', [
            'transaksi' => $transaksi,
            'qrCode' => $qrCodeImage,
            'qrisUrl' => $qrisUrl,
        ]);
    }

    // Generate QRIS URL
    private function generateQrisUrl(Transaksi $transaksi)
    {
        // Static untuk demo, ganti dengan library untuk production
        return "00020126440014id.co.verifone..."; // QRIS string
    }

    // Generate QR Code
    private function generateQrCode($qrisUrl)
    {
        try {
            $qrCode = new QrCode($qrisUrl);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            return base64_encode($result->getString());
        } catch (\Exception $e) {
            return null;
        }
    }

    // Upload bukti pembayaran
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if (Auth::user()->id !== $transaksi->user_id) {
            abort(403);
        }

        try {
            // Hapus file lama
            if ($transaksi->bukti_pembayaran) {
                Storage::disk('public')->delete('bukti_pembayaran/' . $transaksi->bukti_pembayaran);
            }

            // Upload file baru
            $file = $request->file('bukti_pembayaran');
            $filename = 'TRX-' . $transaksi->id . '-' . time() . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'bukti_pembayaran',
                $file,
                $filename
            );

            // Update database
            $transaksi->update([
                'bukti_pembayaran' => $filename,
                'status_pembayaran' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Upload gagal: ' . $e->getMessage());
        }
    }

    // Verifikasi pembayaran (Admin)
    public function verifyPayment(Request $request, $id)
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status_pembayaran' => 'required|in:berhasil,gagal',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        $transaksi->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran diperbarui.');
    }

    // Download bukti
    public function downloadProof($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if (Auth::user()->id !== $transaksi->user_id && Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        if (!$transaksi->bukti_pembayaran) {
            return redirect()->back()->with('error', 'Bukti tidak tersedia');
        }

        $path = storage_path('app/public/bukti_pembayaran/' . $transaksi->bukti_pembayaran);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($path);
    }
}
```

### View: pembeli/payment.blade.php (Preview)
```php
<div class="row g-4">
    {{-- QR CODE --}}
    <div class="col-lg-6">
        <h5 class="fw-bold">Scan QRIS untuk Pembayaran</h5>
        @if($qrCode)
            <div class="qr-code-box">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QRIS" class="qr-code-img">
            </div>
        @endif
    </div>

    {{-- UPLOAD BUKTI --}}
    <div class="col-lg-6">
        <form action="{{ route('pembayaran.uploadProof', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="bukti_pembayaran" accept=".jpg,.png,.pdf" required>
            <button type="submit" class="btn btn-upload">Upload Bukti</button>
        </form>
    </div>
</div>
```

---

## 3. WHATSAPP ADMIN

### Migration
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('whatsapp')->nullable();
});
```

### View: profile/edit.blade.php
```php
@if(auth()->user()->usertype === 'admin')
<div class="mb-4">
    <label class="form-label fw-bold">Nomor WhatsApp</label>
    <input type="tel" name="whatsapp" 
           class="form-control" 
           value="{{ old('whatsapp', $user->whatsapp) }}"
           placeholder="+62812345678">
    <small class="text-muted">Format: +62...</small>
</div>
@endif
```

### Admin Dashboard: transaksi/index.blade.php
```php
@php
    $nomor = $data->no_hp;
    if(substr($nomor, 0, 1) == '0') {
        $nomor = '62' . substr($nomor, 1);
    }

    $pesan = "Halo kak *" . $data->nama_pembeli . "*! 👋%0a";
    $pesan .= "Pesanan: *" . strtoupper($data->jenis_pesanan) . "*%0a";
    $pesan .= "Total: Rp " . number_format($data->total_harga, 0, ',', '.') . "%0a";

    $linkWA = "https://wa.me/$nomor?text=$pesan";
@endphp

<a href="{{ $linkWA }}" target="_blank" class="btn btn-success">
    <i class="bi bi-whatsapp me-1"></i> Chat
</a>
```

---

## 4. UPLOAD BUKTI PEMBAYARAN

### Migration
```php
Schema::table('transaksi', function (Blueprint $table) {
    $table->string('bukti_pembayaran')->nullable();
    $table->enum('status_pembayaran', ['pending', 'berhasil', 'gagal'])->default('pending');
});
```

### Validation (Controller)
```php
$request->validate([
    'bukti_pembayaran' => [
        'required',
        'file',
        'mimes:jpg,jpeg,png,pdf',
        'max:2048', // 2MB
    ],
], [
    'bukti_pembayaran.required' => 'Bukti harus diupload',
    'bukti_pembayaran.mimes' => 'Format harus JPG, PNG, atau PDF',
    'bukti_pembayaran.max' => 'Ukuran max 2MB',
]);
```

### Storage
```php
// Simpan file
$file = $request->file('bukti_pembayaran');
$filename = 'TRX-' . $transaksi->id . '-' . time() . '.' . $file->getClientOriginalExtension();

Storage::disk('public')->putFileAs(
    'bukti_pembayaran',
    $file,
    $filename
);

// URL untuk akses
$url = asset('storage/bukti_pembayaran/' . $filename);
```

### Admin Verification Modal (JavaScript)
```javascript
function showProofModal(transaksiId, fileName, status) {
    const filePath = `/storage/bukti_pembayaran/${fileName}`;
    const extension = fileName.split('.').pop().toLowerCase();

    let preview = '';
    if (['jpg', 'jpeg', 'png'].includes(extension)) {
        preview = `<img src="${filePath}" class="img-fluid" style="max-height: 400px;">`;
    }

    // Show in modal & allow verify
    document.getElementById('proofContent').innerHTML = preview;
    // ... show verify buttons
}

function verifyPayment(status) {
    // POST to /admin/pembayaran/{id}/verify with status
}
```

---

## Environment Setup

### .env
```env
# Facebook OAuth
FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback

# Storage
FILESYSTEM_DISK=public
```

### commands
```bash
# Setup
php artisan migrate
php artisan storage:link

# Clear cache (if needed)
php artisan config:clear
php artisan cache:clear
```

---

## Testing Examples

### Test Facebook Login (Tinker)
```bash
php artisan tinker

# Check user created
>>> User::where('social_provider', 'facebook')->first()
=> App\Models\User {#5555
    id: 1,
    name: "John Doe",
    email: "john@facebook.com",
    social_id: "123456789",
    social_provider: "facebook",
  }
```

### Test QRIS Payment
```bash
# Create test transaksi
>>> $transaksi = Transaksi::factory()->create(['metode_pembayaran' => 'qris']);

# Visit payment page
# http://localhost/pembayaran/1

# Upload test file
# Choose JPG/PNG/PDF max 2MB
```

### Test Admin WhatsApp
```bash
# Update user
>>> $user = User::find(1);
>>> $user->update(['whatsapp' => '+628123456789']);

# Check profile edit page
# /profile/edit
```

---

This guide covers all 4 features dengan kode nyata yang bisa langsung diimplementasikan!
