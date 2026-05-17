<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController; 
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AksesAdmin;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\PaymentController;

// =========================================================================
// ROUTING UMUM
// =========================================================================

Route::get('/ping', function () {
    return 'Server Laravel Aman!';
});

Route::get('/', [PembeliController::class, 'index'])->name('pembeli.index');

// =========================================================================
// SOCIAL LOGIN (FACEBOOK)
// =========================================================================

Route::get('/auth/facebook', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');

// =========================================================================
// ROUTING PEMBELI
// =========================================================================

Route::get('/cart', [PembeliController::class, 'cart'])->name('cart');
Route::post('/add-to-cart/{id}', [PembeliController::class, 'addToCart'])->name('add.to.cart');
Route::delete('/remove-from-cart', [PembeliController::class, 'removeCart'])->name('remove.from.cart');
Route::post('/checkout', [PembeliController::class, 'checkout'])->name('checkout');
Route::get('/set-layanan/{tipe}', [PembeliController::class, 'setLayanan'])->name('set.layanan');

Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-pesanan', [PembeliController::class, 'riwayat'])->name('pembeli.riwayat');
    Route::patch('/update-cart', [PembeliController::class, 'updateCart'])->name('update.cart');
});

// =========================================================================
// ROUTING PEMBAYARAN
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/pembayaran/{id}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{id}/upload-proof', [PaymentController::class, 'uploadProof'])->name('pembayaran.uploadProof');
    Route::get('/pembayaran/{id}/download-proof', [PaymentController::class, 'downloadProof'])->name('pembayaran.downloadProof');
    Route::post('/admin/pembayaran/{id}/verify', [PaymentController::class, 'verifyPayment'])->name('pembayaran.verify');
});

// =========================================================================
// ROUTING CHAT
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::post('/chat/kirim', [ChatController::class, 'kirimPesanPembeli'])->name('chat.kirim');
    Route::get('/admin/chat', [ChatController::class, 'adminChat'])->name('admin.chat');
    Route::get('/admin/chat/messages/{user_id}', [ChatController::class, 'getMessages']);
});

// =========================================================================
// ROUTING DASHBOARD & HOME
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// =========================================================================
// ROUTING PRODUK (ADMIN & KASIR)
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/tampil-produk', [ProdukController::class, 'index']);
    Route::get('/tambah-produk', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/tampil-produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::post('/produk/edit/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::post('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produk.delete');
    Route::get('/produk/export/excel', [ProdukController::class, 'excel'])->name('produk.excel');
    Route::get('/produk/export/pdf', [ProdukController::class, 'pdf'])->name('produk.pdf');
    Route::get('/produk/chart', [ProdukController::class, 'chart'])->name('produk.chart');
});

// =========================================================================
// ROUTING KATEGORI (ADMIN & KASIR)
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/tampil-kategori', [KategoriController::class, 'index']);
    Route::get('/tambah-kategori', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/tampil-kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::post('/kategori/edit/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::post('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.delete');
});

// ================================ =========================================
// ROUTING TRANSAKSI & LAPORAN (ADMIN & KASIR)
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/selesai/{id}', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'cetakStruk'])->name('transaksi.struk');
    Route::post('/admin/transaksi/{id}/kirim', [TransaksiController::class, 'updatePengiriman'])->name('admin.transaksi.kirim');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});

// =========================================================================
// ROUTING KASIR MENU
// =========================================================================

Route::middleware(['auth', AksesAdmin::class])->group(function () {
    Route::get('/kasir/menu', [TransaksiController::class, 'kasirMenu'])->name('transaksi.kasir.menu');
    Route::get('/transaksi/manual', [TransaksiController::class, 'createKasir'])->name('transaksi.kasir.create');
    Route::post('/transaksi/manual', [TransaksiController::class, 'storeKasir'])->name('transaksi.kasir.store');
});

// =========================================================================
// ROUTING ULASAN / REVIEW
// =========================================================================

Route::post('/kirim-ulasan', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/ulasan', [App\Http\Controllers\ReviewController::class, 'index'])->name('admin.reviews');
});

// =========================================================================
// ROUTING PROFILE
// =========================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// =========================================================================
// API ROUTES
// =========================================================================

Route::prefix('api')->group(function () {
    Route::get('/admin/contact-info', [App\Http\Controllers\Api\AdminContactApiController::class, 'getContactInfo'])->name('api.admin.contact-info');
    Route::get('/admin/whatsapp', [App\Http\Controllers\Api\AdminContactApiController::class, 'getWhatsApp'])->name('api.admin.whatsapp');
    Route::get('/admin/whatsapp-link', [App\Http\Controllers\Api\AdminContactApiController::class, 'getWhatsAppLink'])->name('api.admin.whatsapp-link');
    Route::get('/admin/has-whatsapp', [App\Http\Controllers\Api\AdminContactApiController::class, 'hasWhatsApp'])->name('api.admin.has-whatsapp');
    Route::middleware(['auth'])->get('/admin/info', [App\Http\Controllers\Api\AdminContactApiController::class, 'getAdminInfo'])->name('api.admin.info');
});