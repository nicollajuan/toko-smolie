@extends('layouts.master')
@section('title', 'Transaksi Kasir')

@section('content')
<div class="container mt-5">
    <!-- Judul Halaman -->
    <div class="mb-4">
        <h2><i class="fas fa-cash-register"></i> Halaman Transaksi</h2>
    </div>

    <!-- KALIMAT PENANDA PANJANG (Alert Biru) -->
    <div class="alert alert-info shadow-sm border-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-shield-alt"></i> OTORISASI BERHASIL: MODE TRANSAKSI AKTIF</h4>
        <p class="mb-0" style="font-size: 1.1rem; line-height: 1.8;">
            Anda saat ini berada di panel kontrol Transaksi Penjualan. Halaman ini dilengkapi dengan fitur input data langsung yang terhubung ke database inventaris pusat. 
            Demi menjaga integritas stok dan validitas keuangan, halaman ini dilindungi oleh lapisan keamanan Middleware yang hanya mengizinkan personel dengan jabatan <strong>'Kasir'</strong> untuk masuk. 
            Keberadaan Anda di halaman ini menandakan bahwa akun Anda memiliki izin penuh untuk melakukan input penjualan, cetak struk, dan pembatalan transaksi sesuai SOP yang berlaku.
        </p>
        <hr>
        <p class="mb-0"><em>Status Koneksi: <strong>Aman (Terproteksi Middleware)</strong></em></p>
    </div>

    <!-- Tombol Dummy -->
    <div class="mt-4">
        <button class="btn btn-primary btn-lg" disabled>Mulai Transaksi Baru</button>
        <button class="btn btn-outline-secondary btn-lg" disabled>Lihat Riwayat</button>
    </div>
</div>
@endsection