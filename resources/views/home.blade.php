@extends('layouts.master')

@section('title', 'Dashboard Warung')

@section('content')
<style>
    /* CSS Khusus Halaman Home - Disesuaikan dengan Tema Timbuktoon */
    
    /* HERO BANNER STYLE */
    .hero-banner {
        /* Menggunakan gradien warna coral/oranye-merah yang ceria */
        background: linear-gradient(135deg, var(--theme-primary), #FF7A68);
        color: white;
        border-radius: 24px; /* Sudut sangat membulat */
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2); /* Shadow warna tema */
        position: relative;
        overflow: hidden;
    }
    
    .hero-pattern {
        position: absolute; top: -40px; right: -30px;
        opacity: 0.15; font-size: 16rem; color: white;
        transform: rotate(-15deg); pointer-events: none;
    }

    /* CARD MENU STYLE */
    .menu-card {
        border: none;
        border-radius: 24px; /* Sudut sangat membulat */
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04); /* Shadow super halus */
        height: 100%;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        /* Garis bawah tebal saat di-hover dengan warna utama */
        border-bottom: 6px solid var(--theme-primary);
    }

    .icon-wrapper {
        /* Lingkaran icon menggunakan warna background pink muda */
        background-color: var(--theme-secondary);
        width: 80px; height: 80px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 15px;
        font-size: 2.5rem;
        color: var(--theme-primary); /* Icon warna merah bata */
        transition: transform 0.3s ease;
    }

    .menu-card:hover .icon-wrapper {
        transform: scale(1.1); /* Efek membesar sedikit saat di-hover */
    }

    /* Kita tidak perlu lagi CSS tombol khusus (.btn-primary-kfc dll) 
       karena sudah diatur secara global di master.blade.php */

</style>

<div class="container-fluid py-2">
    
    {{-- 1. HERO BANNER --}}
    <div class="row">
        <div class="col-12">
            <div class="hero-banner">
                <div class="position-relative z-1">
                    <h1 class="theme-font display-5 mb-2">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="fs-5 mt-2 opacity-75 fw-bold" style="letter-spacing: 1px;">SISTEM KASIR TAHU LONTONG BAROKAH</p>
                </div>
                <i class="bi bi-shop hero-pattern"></i>
            </div>
        </div>
    </div>

    {{-- 2. SHORTCUT AKSI CEPAT --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 p-4 bg-white" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="theme-font text-dark m-0"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Aksi Cepat</h4>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    {{-- Menggunakan class btn-primary bawaan master.blade yang sudah dimodifikasi --}}
                    <a href="{{ route('pembeli.index') }}" target="_blank" class="btn btn-primary flex-grow-1 py-3 fs-5 shadow-sm">
                        <i class="bi bi-cart-fill me-2"></i> Mode Kasir (Buka Menu)
                    </a>
                    
                    {{-- Menggunakan class btn-outline-secondary --}}
                    <a href="/tampil-produk" class="btn btn-outline-secondary py-3 px-4 shadow-sm">
                        <i class="bi bi-box-seam me-2"></i> Kelola Produk
                    </a>

                    <a href="/laporan" class="btn btn-outline-secondary py-3 px-4 shadow-sm">
                        <i class="bi bi-bar-chart-line me-2"></i> Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. RINGKASAN MENU --}}
    <div class="d-flex align-items-center mb-4">
        <h4 class="theme-font text-dark m-0 me-3">Kategori Menu</h4>
        <div class="flex-grow-1 border-bottom" style="border-style: dashed !important; border-color: #cbd5e1 !important;"></div>
    </div>

    {{-- GRID SYSTEM --}}
    <div class="row g-4 mb-4">
        
        {{-- 1. Makanan --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-egg-fried"></i></div>
                <h5 class="theme-font text-dark">Makanan</h5>
                <span class="badge px-3 py-2 rounded-pill mt-1" style="background-color: #FFF0ED; color: var(--theme-primary);">Utama</span>
            </div>
        </div>

        {{-- 2. Dessert --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-snow2"></i></div>
                <h5 class="theme-font text-dark">Dessert</h5>
                <span class="badge px-3 py-2 rounded-pill mt-1" style="background-color: #E6F7FF; color: #0284C7;">Segar</span>
            </div>
        </div>
        
        {{-- 3. Snack --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-cookie"></i></div> 
                <h5 class="theme-font text-dark">Snack</h5>
                <span class="badge px-3 py-2 rounded-pill mt-1" style="background-color: #FEF3C7; color: #D97706;">Camilan</span>
            </div>
        </div>

        {{-- 4. Minuman --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-cup-straw"></i></div>
                <h5 class="theme-font text-dark">Minuman</h5>
                <span class="badge px-3 py-2 rounded-pill mt-1" style="background-color: #DCFCE7; color: #16A34A;">Dingin</span>
            </div>
        </div>

    </div>

</div>
@endsection