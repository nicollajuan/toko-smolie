<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController; 
use App\Http\Middleware\Kasir;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AksesAdmin;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\PaymentController;


// =========================================================================
#  ROUTING SOCIAL LOGIN (FACEBOOK)
# =========================================================================
// Facebook Routes
Route::get('/auth/facebook', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');

// Rute untuk Update Pengiriman oleh Admin
Route::post('/admin/transaksi/{id}/kirim', [TransaksiController::class, 'updatePengiriman'])->name('admin.transaksi.kirim');

// Rute untuk Admin
Route::get('/admin/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi');
Route::patch('/admin/transaksi/{id}/selesai', [TransaksiController::class, 'selesai'])->name('admin.transaksi.selesai');
Route::get('/admin/transaksi/{id}/struk', [TransaksiController::class, 'cetakStruk'])->name('admin.transaksi.struk');

Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-pesanan', [TransactionController::class, 'history'])->name('pembeli.history');
    Route::get('/cetak-invoice/{id}', [TransactionController::class, 'cetakPdf'])->name('pembeli.cetak_pdf');
});

//hai
Route::middleware(['auth'])->group(function () {
    // Sisi Pembeli & Admin Kirim
    Route::post('/chat/kirim', [ChatController::class, 'kirimPesanPembeli'])->name('chat.kirim');
    
    // Sisi Admin Kelola
    Route::get('/admin/chat', [ChatController::class, 'adminChat'])->name('admin.chat');
    Route::get('/admin/chat/messages/{user_id}', [ChatController::class, 'getMessages']);
});

// Rute ini KHUSUS ADMIN & KASIR
Route::middleware(['auth', AksesAdmin::class])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Rute Produk
    Route::get('/tampil-produk', [App\Http\Controllers\ProdukController::class, 'index']);
    Route::get('/tambah-produk', [App\Http\Controllers\ProdukController::class, 'create']);
    Route::post('/simpan-produk', [App\Http\Controllers\ProdukController::class, 'store']);
    Route::put('/update-produk/{id}', [App\Http\Controllers\ProdukController::class, 'update']);
    Route::delete('/hapus-produk/{id}', [App\Http\Controllers\ProdukController::class, 'destroy']);



});

//routing welcome
Route::get('/', function () {
    return view('welcome');
});

//routing latihan (lat)
Route::get('lat', function () {
    return view('latihan');
});

//routing biodata (bio)
Route::get('bio', function () {
    return view('biodata');
});

//routing nama (nama)
Route::get('nama', function () {
    return view('nama', ['name' => 'Nicolla Juan Ardhan']);
});

//routing nilai1 (nilai1)
Route::get('nilai1', function () {
    return view('getnilai1');
});

//routing nilai2 (nilai2)
Route::get('nilai2', function () {
    return view('getnilai2');
});

#===============TUGAS CONTROLLER==============
Route::get('home', [PraktikumController::class, 'home']);
Route::get('produk', [PraktikumController::class, 'product']);
Route::get('transaksi', [PraktikumController::class, 'transaction']);
Route::get('laporan', [PraktikumController::class, 'report']);

#===============UTS================
Route::get('war', function () {
    return view('warung');
});
// In routes/web.php
Route::resource('kategori', App\Http\Controllers\KategoriController::class);

#===============CRUD - Praktikum 7 (Versi Tanpa Login - Legacy)=================
Route::get('tampil-produk', [ProdukController::class, 'index']);
Route::get('tambah-produk', [ProdukController::class, 'create'])->name('produk.create');
Route::post('tampil-produk', [ProdukController::class, 'store'])->name('produk.store');
Route::put('/produk/update/{id}', [ProdukController::class, 'update'])->name('produk.update');
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

#===============CRUD KATEGORI (Versi Tanpa Login - Legacy)=================
Route::get('tampil-kategori', [KategoriController::class, 'index']);
Route::get('tambah-kategori', [KategoriController::class, 'create'])->name('kategori.create');
Route::post('tampil-kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::put('/kategori/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
Route::get('tampil-laporan', [LaporanController::class, 'index']);


# CRUD export Excel, PDF, Chart
Route::get('/produk/export/excel', [ProdukController::class, 'excel'])->name('produk.excel');
Route::get('/produk/export/pdf', [ProdukController::class, 'pdf'])->name('produk.pdf');
Route::get('/produk/chart', [ProdukController::class, 'chart'])->name('produk.chart');


# =========================================================================
#  ROUTING DASHBOARD ADMIN & KASIR (DIPERBARUI)
# =========================================================================
# Kita ubah middlewarenya menjadi ['auth'] saja agar Admin & Kasir 
# bisa saling akses menu (Sesuai permintaan "Admin bisa semuanya")

// 1. Routing Produk (Bisa Admin & Kasir)
Route::middleware(['auth'])->group(function () {
    Route::get('/tampil-produk', [ProdukController::class, 'index']);
    Route::get('/tambah-produk', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/tampil-produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::post('/produk/edit/{id}', [ProdukController::class, 'update'])->name('produk.update'); // Pakai POST untuk update form
    Route::post('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produk.delete');
});

// 2. Routing Kategori (Bisa Admin & Kasir)
Route::middleware(['auth'])->group(function() {
    Route::get('tampil-kategori', [KategoriController::class, 'index']);
    Route::get('tambah-kategori', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('tampil-kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::post('/kategori/edit/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.delete');
});

// 3. Routing Transaksi & Laporan (Bisa Admin & Kasir)
// Sebelumnya dibatasi Kasir::class, sekarang dibuka untuk Auth agar Admin juga bisa masuk
Route::middleware(['auth'])->group(function () {
    // Halaman Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // TAMBAHAN: Route Export Excel & PDF
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    
    // Halaman Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/selesai/{id}', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
});
    

# =========================================================================
#  ROUTING UMUM (PROFILE & HOME)
# =========================================================================

// Home (Dashboard dengan Pengecekan Role)
Route::middleware(['auth'])->group(function () {
    // Menggunakan HomeController agar Pembeli dilempar ke Menu, Admin ke Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

# =========================================================================
#  ROUTING PEMBELI (MENU DIGITAL)
# =========================================================================
Route::get('/', [PembeliController::class, 'index'])->name('pembeli.index'); // Halaman Awal
Route::get('cart', [PembeliController::class, 'cart'])->name('cart');
Route::post('add-to-cart/{id}', [PembeliController::class, 'addToCart'])->name('add.to.cart');
Route::delete('remove-from-cart', [PembeliController::class, 'removeCart'])->name('remove.from.cart');
Route::post('checkout', [PembeliController::class, 'checkout'])->name('checkout');

Route::middleware(['auth'])->group(function () {
    
    // TAMBAHAN: Route Cetak Struk
    Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'cetakStruk'])->name('transaksi.struk');
});

Route::middleware(['auth'])->group(function () {
    
    // TAMBAHAN: Riwayat Pesanan Pembeli
    Route::get('/riwayat-pesanan', [PembeliController::class, 'riwayat'])->name('pembeli.riwayat');
});

Route::middleware(['auth'])->group(function () {
    
    // TAMBAHAN: Update Jumlah Keranjang
    Route::patch('/update-cart', [PembeliController::class, 'updateCart'])->name('update.cart');
});

// =========================================================================
#  ROUTING PEMBAYARAN (QRIS & BUKTI PEMBAYARAN)
# =========================================================================
Route::middleware(['auth'])->group(function () {
    // Tampilkan halaman pembayaran dengan QRIS
    Route::get('/pembayaran/{id}', [PaymentController::class, 'show'])->name('pembayaran.show');
    
    // Upload bukti pembayaran
    Route::post('/pembayaran/{id}/upload-proof', [PaymentController::class, 'uploadProof'])->name('pembayaran.uploadProof');
    
    // Download bukti pembayaran
    Route::get('/pembayaran/{id}/download-proof', [PaymentController::class, 'downloadProof'])->name('pembayaran.downloadProof');
});

// Admin verifikasi pembayaran
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/pembayaran/{id}/verify', [PaymentController::class, 'verifyPayment'])->name('pembayaran.verify');
});

Route::middleware(['auth'])->group(function () {

    
    // Route untuk set jenis layanan (Dine In / Takeaway / Delivery)
    Route::get('/set-layanan/{tipe}', [PembeliController::class, 'setLayanan'])->name('set.layanan');
});


Route::post('/kirim-ulasan', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');

Route::group(['middleware' => ['auth']], function () {

    
    // Route untuk melihat ulasan
    Route::get('/admin/ulasan', [App\Http\Controllers\ReviewController::class, 'index'])->name('admin.reviews');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

# =========================================================================
#  API ROUTES - ADMIN CONTACT INFO (NEW FEATURE)
# =========================================================================
Route::prefix('api')->group(function () {
    Route::get('/admin/contact-info', [App\Http\Controllers\Api\AdminContactApiController::class, 'getContactInfo'])->name('api.admin.contact-info');
    Route::get('/admin/whatsapp', [App\Http\Controllers\Api\AdminContactApiController::class, 'getWhatsApp'])->name('api.admin.whatsapp');
    Route::get('/admin/whatsapp-link', [App\Http\Controllers\Api\AdminContactApiController::class, 'getWhatsAppLink'])->name('api.admin.whatsapp-link');
    Route::get('/admin/has-whatsapp', [App\Http\Controllers\Api\AdminContactApiController::class, 'hasWhatsApp'])->name('api.admin.has-whatsapp');
    
    // Memerlukan autentikasi
    Route::middleware(['auth'])->get('/admin/info', [App\Http\Controllers\Api\AdminContactApiController::class, 'getAdminInfo'])->name('api.admin.info');
});