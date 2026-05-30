@extends('layouts.master')

@section('title', 'Dashboard Smolie Gift')

@section('content')
<style>
    :root {
        --smolie-red: #DD3827;
        --smolie-light: #FFF0ED;
    }

    /* Peningkatan responsivitas padding di mobile */
    .hero-banner {
        background: linear-gradient(135deg, var(--smolie-red), #FF7A68);
        color: white;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    @media (min-width: 768px) {
        .hero-banner {
            padding: 40px;
        }
    }
    
    .hero-pattern {
        position: absolute; top: -30px; right: -20px;
        opacity: 0.15; font-size: 18rem; color: white;
        transform: rotate(-15deg); pointer-events: none;
    }

    .menu-card {
        border: 2px solid #f1f3f5;
        border-radius: 20px; 
        background: white;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.03); 
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: default;
    }
    
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(221, 56, 39, 0.15);
        border-color: var(--smolie-red);
    }

    .icon-wrapper {
        background-color: var(--smolie-light);
        width: 80px; height: 80px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 15px;
        font-size: 2.5rem;
        color: var(--smolie-red); 
        transition: transform 0.3s ease;
    }
    
    .menu-card:hover .icon-wrapper {
        transform: scale(1.1);
    }

    /* Tombol Aksi Raksasa */
    .btn-aksi-besar {
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-aksi-besar:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-aksi-besar:active {
        transform: translateY(1px);
    }
</style>

<div class="container-fluid py-3 px-lg-4">
    
    {{-- 1. HERO BANNER --}}
    <div class="row">
        <div class="col-12">
            <div class="hero-banner">
                <div class="position-relative z-1">
                    <h1 class="display-5 fw-bold mb-2">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="fs-5 mt-2 opacity-75 fw-bold" style="letter-spacing: 1px;">DASHBOARD ADMIN SMOLIE GIFT</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. SHORTCUT AKSI CEPAT --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 p-3 p-md-4 bg-white" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="theme-font text-dark m-0">Akses Cepat</h4>
                </div>
                
                <div class="row g-3">
                    {{-- DIUBAH: Dari Buka Mesin Kasir menjadi Preview Katalog --}}
                    <div class="col-12 col-md-4">
                        <a href="{{ url('/') }}" target="_blank" class="btn text-white w-100 py-3 py-md-4 btn-aksi-besar shadow-sm d-flex align-items-center justify-content-center" style="background-color: #DD3827;" aria-label="Preview katalog produk pembeli">
                            <i class="bi bi-shop-window me-2 fs-4"></i> Preview Katalog
                        </a>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <a href="/tampil-produk" class="btn btn-outline-dark w-100 py-3 py-md-4 btn-aksi-besar shadow-sm border-2 d-flex align-items-center justify-content-center" aria-label="Buka halaman kelola souvenir">
                            <i class="bi bi-box-seam me-2 fs-4"></i> Kelola Souvenir
                        </a>
                    </div>

                    <div class="col-12 col-md-4">
                        <a href="/laporan" class="btn btn-outline-dark w-100 py-3 py-md-4 btn-aksi-besar shadow-sm border-2 d-flex align-items-center justify-content-center" aria-label="Buka halaman laporan penjualan">
                            <i class="bi bi-bar-chart-line me-2 fs-4"></i> Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. RINGKASAN KATEGORI SOUVENIR --}}
    <div class="d-flex align-items-center mb-4">
        <h3 class="fw-bold text-dark m-0 me-3">Kategori Produk</h3>
        <div class="flex-grow-1 border-bottom" style="border-style: dashed !important; border-width: 2px !important; border-color: #cbd5e1 !important;"></div>
    </div>

    {{-- GRID SYSTEM KATEGORI --}}
    <div class="row g-3 g-md-4 mb-4">
        
        <div class="col-6 col-md-3">
            <div class="menu-card p-3 p-md-4 text-center">
                <div class="icon-wrapper" aria-hidden="true"><i class="bi bi-cup-hot-fill"></i></div>
                <h4 class="fw-bold text-dark mb-2 fs-5">Alat Makan</h4>
                <span class="badge px-2 py-1 px-md-3 py-md-2 rounded-pill fs-6" style="background-color: #FFF0ED; color: #DD3827;">Piring / Gelas</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="menu-card p-3 p-md-4 text-center">
                <div class="icon-wrapper" aria-hidden="true"><i class="bi bi-lamp-fill text-primary"></i></div>
                <h4 class="fw-bold text-dark mb-2 fs-5">Furniture</h4>
                <span class="badge px-2 py-1 px-md-3 py-md-2 rounded-pill fs-6" style="background-color: #E6F7FF; color: #0284C7;">Dekorasi</span>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="menu-card p-3 p-md-4 text-center">
                <div class="icon-wrapper" aria-hidden="true"><i class="bi bi-house-heart-fill text-warning"></i></div> 
                <h4 class="fw-bold text-dark mb-2 fs-5">Rumah Tangga</h4>
                <span class="badge px-2 py-1 px-md-3 py-md-2 rounded-pill fs-6" style="background-color: #FEF3C7; color: #D97706;">Fungsional</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="menu-card p-3 p-md-4 text-center">
                <div class="icon-wrapper" aria-hidden="true"><i class="bi bi-box2-heart-fill text-success"></i></div>
                <h4 class="fw-bold text-dark mb-2 fs-5">Pernikahan</h4>
                <span class="badge px-2 py-1 px-md-3 py-md-2 rounded-pill fs-6" style="background-color: #DCFCE7; color: #16A34A;">Kado Spesial</span>
            </div>
        </div>

    </div>
</div>
@endsection