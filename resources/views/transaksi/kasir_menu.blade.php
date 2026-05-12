@extends('layouts.master')
@section('title', 'Katalog Kasir - Smolie Gift')

@section('content')
<div class="container-fluid py-4 px-lg-4">
    {{-- Header & Buttons --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-7 mb-3 mb-md-0">
            <h2 class="fw-bold text-dark"><i class="bi bi-cart-plus me-2"></i> Katalog Produk Kasir</h2>
            <p class="text-muted mb-0">Pilih produk untuk ditambahkan ke keranjang, atau input manual untuk pesanan khusus.</p>
        </div>
        <div class="col-md-5 text-md-end">
            <a href="{{ route('transaksi.kasir.create') }}" class="btn btn-warning shadow-sm mb-2 mb-xl-0 fw-bold me-1 text-dark">
                <i class="bi bi-pencil-square me-1"></i> Input Manual
            </a>
            <a href="{{ route('cart') }}" class="btn btn-danger shadow-sm mb-2 mb-xl-0 fw-bold" style="background-color: #e4002b; border: none;">
                <i class="bi bi-bag-fill me-1"></i> Lihat Keranjang
            </a>
        </div>
    </div>

    {{-- Fitur Pencarian --}}
    <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                <input type="text" name="cari" class="form-control bg-light border-0" placeholder="Ketik nama produk yang dicari pembeli..." value="{{ request('cari') }}">
                <button type="submit" class="btn btn-danger px-4 fw-bold" style="background-color: #e4002b; border: none;">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary fw-bold">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="row g-4">
        @forelse($produk as $item)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 15px;">
                    {{-- Area Gambar --}}
                    <div class="ratio ratio-4x3 bg-light">
                        @if($item->gambar)
                            <img src="{{ asset('img/produk/'.$item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Area Info Produk --}}
                    <div class="card-body d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                            <div>
                                <h6 class="card-title mb-1 fw-bold text-dark" style="line-height: 1.3;">{{ $item->nama_produk }}</h6>
                                <p class="text-muted small mb-0">{{ $item->kategori?->nama_kategori ?? 'Umum' }}</p>
                            </div>
                            <span class="badge bg-success rounded-pill shadow-sm">Stok {{ $item->stock }}</span>
                        </div>
                        
                        <p class="fw-bold text-danger fs-5 mb-3 mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>

                        {{-- Form Tambah Keranjang --}}
                        <form action="{{ route('add.to.cart', $item->id) }}" method="POST" class="mt-auto">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="number" name="qty" class="form-control text-center fw-bold bg-light border-secondary-subtle" min="1" max="{{ $item->stock }}" value="1" style="max-width: 60px;" required />
                                <button type="submit" class="btn btn-danger flex-grow-1 fw-bold shadow-sm" style="background-color: #e4002b; border: none;">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            {{-- Tampilan jika produk tidak ditemukan / kosong --}}
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold text-dark">Produk tidak ditemukan</h5>
                <p class="text-muted">Coba gunakan kata kunci lain atau gunakan tombol Input Manual.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection