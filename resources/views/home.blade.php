@extends('layouts.master')

@section('title', 'Dashboard Warung')

@section('content')
<style>
    :root {
        --kfc-red: #e4002b;
        --kfc-gray: #f8f9fa;
        --kfc-text: #202124;
    }
    
    body { background-color: var(--kfc-gray); }

    /* HERO BANNER STYLE */
    .hero-banner {
        background: linear-gradient(135deg, var(--kfc-red), #ff4d4d);
        color: white;
        border-radius: 15px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(228, 0, 43, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .hero-pattern {
        position: absolute; top: -50px; right: -50px;
        opacity: 0.1; font-size: 15rem; color: white;
        transform: rotate(-15deg); pointer-events: none;
    }

    /* CARD MENU STYLE */
    .menu-card {
        border: none;
        border-radius: 12px;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-bottom: 4px solid var(--kfc-red);
    }

    .icon-wrapper {
        background-color: #fff5f5;
        width: 70px; height: 70px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 15px;
        font-size: 2rem;
        color: var(--kfc-red);
    }

    /* ACTION BUTTONS */
    .btn-action {
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        transition: 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary-kfc {
        background-color: var(--kfc-red);
        border: none; color: white;
    }
    .btn-primary-kfc:hover { background-color: #c40025; color: white; }
    
    .btn-outline-kfc {
        border: 2px solid var(--kfc-text);
        color: var(--kfc-text); background: transparent;
    }
    .btn-outline-kfc:hover { background-color: var(--kfc-text); color: white; }

</style>

<div class="container-fluid py-3">
    
    {{-- 1. HERO BANNER --}}
    <div class="row">
        <div class="col-12">
            <div class="hero-banner">
                <div class="position-relative z-1">
                    <h1 class="fw-bold display-5">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="fs-5 mt-2 opacity-90">Tahu Lontong Barokah</p>
                </div>
                <i class="bi bi-shop hero-pattern"></i>
            </div>
        </div>
    </div>

    {{-- 2. SHORTCUT AKSI CEPAT --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark m-0">⚡ Aksi Cepat</h4>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    {{-- Tombol Buka Menu Pembeli --}}
                    <a href="{{ route('pembeli.index') }}" target="_blank" class="btn btn-primary-kfc btn-action flex-grow-1">
                        <i class="bi bi-cart-fill me-2"></i> Mode Kasir (Buka Menu)
                    </a>
                    
                    {{-- Tombol Kelola Produk --}}
                    <a href="/tampil-produk" class="btn btn-outline-kfc btn-action">
                        <i class="bi bi-box-seam me-2"></i> Kelola Produk
                    </a>

                    {{-- Tombol Lihat Laporan --}}
                    <a href="/laporan" class="btn btn-outline-kfc btn-action">
                        <i class="bi bi-bar-chart-line me-2"></i> Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. RINGKASAN MENU --}}
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold text-dark m-0 me-3">Kategori Menu</h4>
        <div class="flex-grow-1 border-bottom"></div>
    </div>

    {{-- GRID SYSTEM YANG BENAR (1 ROW UNTUK 4 KOLOM) --}}
    <div class="row g-4">
        
        {{-- 1. Makanan --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-egg-fried"></i></div>
                <h5 class="fw-bold text-dark">Makanan</h5>
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Utama</span>
            </div>
        </div>

        {{-- 2. Dessert --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-snow2"></i></div>
                <h5 class="fw-bold text-dark">Dessert</h5>
                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Segar</span>
            </div>
        </div>
        
        {{-- 3. Snack --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                {{-- Ganti Icon jadi Cookie --}}
                <div class="icon-wrapper"><i class="bi bi-cookie"></i></div> 
                <h5 class="fw-bold text-dark">Snack</h5>
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Camilan</span>
            </div>
        </div>

        {{-- 4. Minuman --}}
        <div class="col-md-3 col-6">
            <div class="menu-card p-4 text-center">
                <div class="icon-wrapper"><i class="bi bi-cup-straw"></i></div>
                <h5 class="fw-bold text-dark">Minuman</h5>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Dingin</span>
            </div>
        </div>

    </div> {{-- Penutup Row Grid --}}

</div>
@endsection