@extends('layouts.master')
@section('title', 'Katalog Kasir - Smolie Gift')

@section('content')

{{-- STYLE KONSISTEN DENGAN TEMA SMOLIE GIFT --}}
<style>
    :root {
        --smolie-red: #DD3827;
        --text-dark: #202124;
        --bg-light: #F9F9FB;
    }

    /* Tipografi */
    .theme-font {
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
    }

    /* Tombol Standar */
    .btn-smolie {
        border-radius: 50px;
        font-weight: 600;
        padding: 10px 24px;
        transition: all 0.2s ease-in-out;
        font-family: 'Poppins', sans-serif;
        display: inline-flex;
        align-items: center;
        border: none;
    }
    .btn-smolie-red { background-color: var(--smolie-red); color: white; }
    .btn-smolie-red:hover { background-color: #C02E1F; color: white; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(221, 56, 39, 0.2); }
    
    .btn-smolie-warning { background-color: #ffc107; color: #000; }
    .btn-smolie-warning:hover { background-color: #ffca2c; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(255, 193, 7, 0.2); }

    /* Search Bar Ringkas */
    .search-wrapper {
        background: white;
        border-radius: 50px;
        padding: 6px 6px 6px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #f1f3f5;
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }
    .search-input {
        border: none;
        background: transparent;
        box-shadow: none !important;
        font-size: 0.95rem;
    }

    /* Kartu Produk */
    .card-smolie {
        border-radius: 20px;
        border: 1px solid #f8f9fa;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        background: white;
        overflow: hidden;
    }
    .card-smolie:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: #f1f3f5;
    }

    /* Gambar Produk */
    .img-wrapper {
        height: 160px;
        position: relative;
        background-color: #f8f9fa;
    }
    .img-produk {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Input Qty & Tombol Tambah Menyatu */
    .form-add-cart {
        display: flex;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        background: white;
        padding: 2px;
    }
    .input-qty-kasir {
        border: none;
        text-align: center;
        font-weight: bold;
        width: 50px;
        background: transparent;
        box-shadow: none !important;
        color: var(--text-dark);
    }
    .btn-tambah-kasir {
        border-radius: 50px;
        background-color: var(--smolie-red);
        color: white;
        border: none;
        font-weight: 600;
        padding: 6px 15px;
        flex-grow: 1;
        transition: 0.2s;
    }
    .btn-tambah-kasir:hover { background-color: #C02E1F; }
</style>

<div class="container-fluid py-4 px-lg-4">
    
    {{-- 1. HEADER & TOMBOL --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1 theme-font text-uppercase">
                <i class="bi bi-shop-window me-2" style="color: var(--smolie-red);"></i>Katalog Kasir
            </h2>
            <p class="text-muted mb-0 fs-6">Pilih produk untuk keranjang, atau input manual untuk pesanan khusus.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('transaksi.kasir.create') }}" class="btn-smolie btn-smolie-warning shadow-sm">
                <i class="bi bi-pencil-square me-2"></i> Input Manual
            </a>
            <a href="{{ route('cart') }}" class="btn-smolie btn-smolie-red shadow-sm position-relative">
                <i class="bi bi-cart-fill me-2"></i> Lihat Keranjang
                {{-- Badge Notif Keranjang (opsional jika ada session cart) --}}
                @if(session('cart'))
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark border border-2 border-white">
                        {{ count((array) session('cart')) }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    {{-- 2. PENCARIAN (SEARCH BAR ROUNDED) --}}
    <div class="row mb-2">
        <div class="col-md-8 col-lg-6">
            <form action="{{ url()->current() }}" method="GET" class="search-wrapper">
                <i class="bi bi-search text-muted fs-5 me-2"></i>
                <input type="text" name="cari" class="form-control search-input flex-grow-1" placeholder="Cari nama menu/produk..." value="{{ request('cari') }}">
                
                @if(request('cari'))
                    <a href="{{ url()->current() }}" class="btn btn-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Reset Pencarian">
                        <i class="bi bi-x-lg text-muted"></i>
                    </a>
                @endif
                
                <button type="submit" class="btn-smolie btn-smolie-red px-4 py-2 m-0">Cari</button>
            </form>
        </div>
    </div>

    {{-- 3. GRID PRODUK --}}
    <div class="row g-3 g-md-4">
        @forelse($produk as $item)
            <div class="col-6 col-md-4 col-lg-3"> {{-- 2 Kolom di HP, 3 di Tablet, 4 di PC --}}
                <div class="card card-smolie h-100 d-flex flex-column">
                    
                    {{-- Area Gambar --}}
                    <div class="img-wrapper">
                        @if($item->gambar)
                            <img src="{{ asset('img/produk/'.$item->gambar) }}" alt="Foto {{ $item->nama_produk }}" class="img-produk">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        {{-- Badge Stok Minimalis --}}
                        <span class="badge bg-white text-dark position-absolute top-0 end-0 m-2 shadow-sm rounded-pill fw-bold border" style="font-size: 0.75rem;">
                            Stok: {{ $item->stock }}
                        </span>
                    </div>
                    
                    {{-- Area Info --}}
                    <div class="card-body d-flex flex-column p-3">
                        <div class="mb-auto">
                            <h6 class="fw-bold theme-font text-truncate mb-0" style="font-size: 0.95rem;">{{ $item->nama_produk }}</h6>
                            <small class="text-muted fw-semibold" style="font-size: 0.75rem;">{{ $item->kategori?->nama_kategori ?? 'Umum' }}</small>
                            <div class="fw-bold mt-1 mb-3" style="color: var(--smolie-red); font-size: 1.1rem;">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Form Tambah menyatu (Pill Shape) --}}
                        <form action="{{ route('add.to.cart', $item->id) }}" method="POST">
                            @csrf
                            <div class="form-add-cart">
                                <input type="number" name="qty" class="form-control input-qty-kasir" min="1" max="{{ $item->stock }}" value="1" required />
                                <button type="submit" class="btn-tambah-kasir">
                                    <i class="bi bi-cart-plus"></i> <span class="d-none d-sm-inline ms-1">Tambah</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            {{-- State Kosong --}}
            <div class="col-12 text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-3" style="width: 100px; height: 100px;">
                    <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold theme-font">Produk Tidak Ditemukan</h4>
                <p class="text-muted">Coba kata kunci lain atau gunakan tombol "Input Manual".</p>
                <a href="{{ url()->current() }}" class="btn-smolie btn-smolie-red mt-2">Lihat Semua Produk</a>
            </div>
        @endforelse
    </div>
</div>
@endsection