<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Tahu Lontong - Order</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-color: #e4002b; --bg-light: #f8f9fa; --text-dark: #202124; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); padding-top: 85px; padding-bottom: 100px; }
        .navbar-kfc { background-color: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 12px 0; position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 1030; }
        .navbar-brand { font-weight: 800; color: var(--text-dark) !important; font-size: 1.2rem; }
        .nav-link { font-weight: 600; color: #555 !important; margin: 0 10px; position: relative; }
        .nav-link:hover, .nav-link.active { color: var(--primary-color) !important; }
        .nav-link.active::after { content: ''; position: absolute; width: 50%; height: 3px; background: var(--primary-color); bottom: -5px; left: 25%; border-radius: 10px; }
        .category-scroll { display: flex; overflow-x: auto; gap: 10px; padding-bottom: 10px; margin-bottom: 20px; scrollbar-width: none; }
        .category-scroll::-webkit-scrollbar { display: none; }
        .btn-cat { border: 1px solid #ddd; background: white; color: #555; border-radius: 50px; padding: 8px 20px; font-weight: 600; white-space: nowrap; transition: all 0.3s; }
        .btn-cat:hover, .btn-cat.active { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
        .hero-banner { background: linear-gradient(135deg, #e4002b, #ff6b6b); border-radius: 20px; padding: 40px 20px; text-align: center; color: white; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(228, 0, 43, 0.2); }
        .service-option { background: white; border-radius: 15px; padding: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.2s; border: 2px solid transparent; cursor: pointer; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #555; text-decoration: none; }
        .service-option:hover { transform: translateY(-3px); border-color: #ffe6e6; }
        .active-service { background-color: #ffe6e6 !important; border: 2px solid var(--primary-color) !important; color: var(--primary-color) !important; box-shadow: 0 5px 15px rgba(228, 0, 43, 0.2) !important; font-weight: bold; }
        .product-card { border: none; border-radius: 15px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; overflow: hidden; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .product-img-wrapper { height: 160px; overflow: hidden; position: relative; background: #eee; }
        .product-img { width: 100%; height: 100%; object-fit: cover; }
        .btn-add { background-color: var(--primary-color); color: white; border: none; border-radius: 30px; padding: 8px 0; font-weight: 600; width: 100%; transition: 0.2s; }
        .btn-add:hover { background-color: #c00024; color: white; }
        .modal-content { border-radius: 20px; border: none; }
        .qty-control { background: #f1f2f6; border-radius: 30px; padding: 3px; display: flex; align-items: center; }
        .btn-qty { width: 32px; height: 32px; border-radius: 50%; border: none; background: white; color: var(--primary-color); font-weight: bold; }
        .input-qty { width: 40px; border: none; background: transparent; text-align: center; font-weight: bold; }
        .floating-cart { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: var(--text-dark); color: white; padding: 15px 25px; border-radius: 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); z-index: 1050; text-decoration: none; }
        .floating-cart:hover { transform: translateX(-50%) scale(1.02); color: white; background: black; }
    </style>
</head>
<body id="beranda">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-kfc fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('template/img/tahu.png') }}" width="40" height="40" class="me-2 rounded-circle border">
                <span class="ms-2">Tahu Lontong</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#menu-area">Menu</a></li>
                    
                    {{-- MENU LOKASI --}}
                    <li class="nav-item">
                        <a class="nav-link" href="https://maps.app.goo.gl/QXPS4XWyi1L5ofAo7" target="_blank">Lokasi</a>
                    </li>
                    
                    @auth <li class="nav-item"><a class="nav-link" href="{{ route('pembeli.riwayat') }}">Riwayat</a></li> @endauth
                </ul>
                <div class="d-flex align-items-center mt-3 mt-lg-0">
                    <a href="{{ route('cart') }}" class="me-3 text-dark position-relative fs-5">
                        <i class="bi bi-bag"></i>
                        @if(session('cart')) <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ count((array) session('cart')) }}</span> @endif
                    </a>
                    @auth
                        <div class="dropdown">
                            <a class="btn btn-outline-danger rounded-pill fw-bold px-4" href="#" role="button" data-bs-toggle="dropdown">{{ explode(' ', Auth::user()->name)[0] }}</a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                                @if(in_array(Auth::user()->usertype, ['admin', 'kasir'])) <li><a class="dropdown-item" href="/home">Dashboard</a></li> @endif
                                <li><form action="{{ route('logout') }}" method="POST">@csrf <button class="dropdown-item text-danger">Logout</button></form></li>
                            </ul>
                        </div>
                    @else
                        <a href="/login" class="btn btn-danger rounded-pill px-4 fw-bold" style="background: var(--primary-color); border:none;">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero-banner">
            <h2 class="fw-bold mb-1">Lapar? Pesan Sekarang!</h2>
            <p class="mb-0 opacity-75">Nikmati Tahu Lontong Legendaris</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Layanan --}}
        <div class="mb-5">
            <h5 class="fw-bold mb-3 ps-1">Mau makan gimana?</h5>
            @php $layanan = session('jenis_pesanan', 'takeaway'); @endphp
            <div class="row g-3">
                <div class="col-4"><a href="{{ route('set.layanan', 'takeaway') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'takeaway' ? 'active-service' : '' }}"><i class="bi bi-bag-check-fill service-icon"></i><div class="fw-bold small">Takeaway</div></div></a></div>
                <div class="col-4"><a href="{{ route('set.layanan', 'dine_in') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'dine_in' ? 'active-service' : '' }}"><i class="bi bi-shop service-icon"></i><div class="fw-bold small">Dine In</div></div></a></div>
                <div class="col-4"><a href="{{ route('set.layanan', 'delivery') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'delivery' ? 'active-service' : '' }}"><i class="bi bi-box-seam-fill service-icon"></i><div class="fw-bold small">Delivery</div></div></a></div>
            </div>
        </div>

        {{-- Menu Area --}}
        <div id="menu-area" class="pt-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold text-dark mb-0">Menu Pilihan</h4>
            </div>

            {{-- Filter Kategori --}}
            <div class="category-scroll">
                <button class="btn btn-cat active" data-filter="all">Semua Menu</button>
                @foreach($kategori as $k)
                    <button class="btn btn-cat" data-filter="{{ $k->id }}">{{ $k->nama_kategori }}</button>
                @endforeach
            </div>
            
            {{-- Grid Produk --}}
            <div class="row g-4 product-container">
                @foreach($produk as $p)
                
                {{-- LOGIKA CEK STATUS NON-AKTIF --}}
                @php 
                    $isNonAktif = ($p->status == 'non-aktif') || ($p->stock <= 0); 
                @endphp

                <div class="col-6 col-md-4 col-lg-3 product-item" data-category="{{ $p->kategori_id }}">
                    {{-- Tambahkan style opacity jika non-aktif --}}
                    <div class="card product-card h-100 {{ $isNonAktif ? 'bg-light border-0' : '' }}" style="{{ $isNonAktif ? 'opacity: 0.7;' : '' }}">
                        
                        <div class="product-img-wrapper position-relative">
                            {{-- Gambar --}}
                            @if($p->gambar) 
                                <img src="{{ asset('img/produk/'.$p->gambar) }}" class="product-img" style="{{ $isNonAktif ? 'filter: grayscale(100%);' : '' }}"> 
                            @else 
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted"><i class="bi bi-image fs-1"></i></div> 
                            @endif

                            {{-- Badge Jika Habis/Non-Aktif --}}
                            @if($isNonAktif)
                                <div class="position-absolute top-50 start-50 translate-middle badge bg-dark px-3 py-2 text-uppercase shadow">
                                    {{ $p->status == 'non-aktif' ? 'Tidak Tersedia' : 'Habis' }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <div class="mb-2">
                                <h6 class="fw-bold text-dark mb-1 text-truncate {{ $isNonAktif ? 'text-muted' : '' }}">{{ $p->nama_produk }}</h6>
                                <div class="text-muted small mb-2" style="font-size: 0.75rem;">{{ $p->kategori?->nama_kategori }}</div>
                                
                                {{-- Harga --}}
                                <div class="{{ $isNonAktif ? 'text-muted text-decoration-line-through' : 'text-danger fw-bold' }}">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-2 border-top">
                                {{-- LOGIKA TOMBOL --}}
                                @if($isNonAktif)
                                    <button type="button" class="btn btn-secondary w-100 btn-sm rounded-pill" disabled style="background-color: #ccc; border:none;">
                                        <i class="bi bi-x-circle me-1"></i> Tidak Tersedia
                                    </button>
                                @else
                                    <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalOrder-{{ $p->id }}">Tambah</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL PESANAN (Hanya Render Jika Aktif) --}}
                @if(!$isNonAktif)
                <div class="modal fade" id="modalOrder-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">{{ $p->nama_produk }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('add.to.cart', $p->id) }}" method="GET">
                                <div class="modal-body">
                                    
                                    {{-- LOGIKA DETEKSI KATEGORI --}}
                                    @php 
                                        $namaKategori = strtolower($p->kategori->nama_kategori ?? '');
                                        
                                        $isDessert = str_contains($namaKategori, 'dessert') || str_contains($namaKategori, 'campur');
                                        $isMinuman = !$isDessert && (str_contains($namaKategori, 'minuman') || str_contains($namaKategori, 'drink'));
                                        $isSnack   = str_contains($namaKategori, 'snack') || str_contains($namaKategori, 'cemilan');
                                    @endphp

                                    @if($isDessert)
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">

                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Pilihan Es</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Es Normal" checked><label class="form-check-label small">Normal Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Extra Es"><label class="form-check-label small">Extra Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Tanpa Es"><label class="form-check-label small">Tanpa Es</label></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Opsi Susu</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_susu" value="Pakai Susu" checked><label class="form-check-label small">Pakai Susu</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_susu" value="Tanpa Susu"><label class="form-check-label small">Tanpa Susu</label></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Tambah Buah <span class="badge bg-light text-dark border ms-1">+Rp 2.000</span></label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach(['Semangka', 'Melon', 'Nanas', 'Nangka', 'Blewah'] as $buah)
                                                <div class="form-check border px-3 py-2 rounded-3">
                                                    <input class="form-check-input addon-check" type="checkbox" name="buah[]" value="{{ $buah }}" data-price="2000" onchange="hitungTotal({{ $p->id }})">
                                                    <label class="form-check-label small">{{ $buah }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="fw-bold small text-muted mb-1">Extra Lainnya</label>
                                            <div class="list-group">
                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border-0 ps-0">
                                                    <div class="d-flex gap-2">
                                                        <input class="form-check-input flex-shrink-0 addon-check" type="checkbox" name="extra_jelly" value="Extra Jelly" data-price="2000" onchange="hitungTotal({{ $p->id }})">
                                                        <span>Extra Jelly/Cincau</span>
                                                    </div>
                                                    <span class="small text-danger fw-bold">+Rp 2.000</span>
                                                </label>
                                            </div>
                                        </div>

                                    @elseif($isMinuman)
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Pilihan Es</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Es Normal" checked><label class="form-check-label small">Normal Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Sedikit Es"><label class="form-check-label small">Sedikit Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="radio" name="opsi_es" value="Hangat"><label class="form-check-label small">Hangat (No Es)</label></div>
                                            </div>
                                        </div>

                                    @elseif($isSnack)
                                        {{-- === BAGIAN KHUSUS SNACK === --}}
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                        
                                    

                                        

                                    @else
                                        {{-- --- BAGIAN MAKANAN BERAT / UTAMA --- --}}
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">

                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Level Pedas</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-danger"><i class="bi bi-fire"></i></span>
                                                <input type="number" name="cabe" class="form-control" placeholder="0 (Tidak Pedas)" min="0" max="20">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold small text-muted mb-1">Request Toping</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="checkbox" name="tanpa_bawang" id="noBawang{{ $p->id }}"><label class="form-check-label small" for="noBawang{{ $p->id }}">No Bawang</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="checkbox" name="tanpa_timun" id="noTimun{{ $p->id }}"><label class="form-check-label small" for="noTimun{{ $p->id }}">No Timun</label></div>
                                                <div class="form-check border px-3 py-2 rounded-3"><input class="form-check-input" type="checkbox" name="tanpa_seledri" id="noSeledri{{ $p->id }}"><label class="form-check-label small" for="noSeledri{{ $p->id }}">No Seledri</label></div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="fw-bold small text-muted mb-1">Ekstra</label>
                                            <div class="list-group">
                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border-0 ps-0">
                                                    <div class="d-flex gap-2">
                                                        <input class="form-check-input flex-shrink-0 addon-check" type="checkbox" name="tambah_telur" value="Telur Dadar" data-price="4000" onchange="hitungTotal({{ $p->id }})">
                                                        <span>Tambah Telur Dadar</span>
                                                    </div>
                                                    <span class="small text-danger fw-bold">+Rp 4.000</span>
                                                </label>

                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border-0 ps-0">
                                                    <div class="d-flex gap-2">
                                                        <input class="form-check-input flex-shrink-0 addon-check" type="checkbox" name="extra_lontong" value="Extra Lontong" data-price="3000" onchange="hitungTotal({{ $p->id }})">
                                                        <span>Extra Lontong</span>
                                                    </div>
                                                    <span class="small text-danger fw-bold">+Rp 3.000</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div class="qty-control">
                                            <button type="button" class="btn-qty" onclick="kurangModal({{ $p->id }})"><i class="bi bi-dash"></i></button>
                                            <input type="number" name="quantity" id="qtyModal-{{ $p->id }}" class="input-qty" value="1" min="1" readonly>
                                            <button type="button" class="btn-qty" onclick="tambahModal({{ $p->id }})"><i class="bi bi-plus"></i></button>
                                        </div>
                                        <button type="submit" id="btn-submit-{{ $p->id }}" class="btn btn-danger rounded-pill px-4 fw-bold" style="background: var(--primary-color);">Masuk Keranjang - Rp {{ number_format($p->harga, 0, ',', '.') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                @endforeach
            </div>
        </div>
    </div>
    {{-- ================= FOOTER ================= --}}
    <footer style="background-color: #181818; color: #b0b0b0; font-family: 'Poppins', sans-serif;">
        
        {{-- Garis Merah di Atas --}}
        <div style="height: 5px; background-color: #e4002b; width: 100%;"></div>

        <div class="container py-5">
            <div class="row g-4">
                
                {{-- KOLOM 1: BRAND & INFO --}}
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                        <img src="{{ asset('template/img/tahu.png') }}" alt="Logo" width="35" height="35" class="me-2 rounded-circle bg-white p-1">
                        WARUNG TAHU
                    </h5>
                    <p class="small mb-4" style="line-height: 1.6;">
                        Menyajikan Tahu Lontong & Tahu Tek dengan resep bumbu petis legendaris sejak 2010. Rasakan kenikmatan asli Jawa Timur.
                    </p>
                    
                    <ul class="list-unstyled small">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i> 
                            <span>Jl. Sukorejo Indah No. 314, Katang</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-clock-fill text-danger me-2"></i> 
                            <span>Buka: 10.00 - 16.00 WIB</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-danger me-2"></i> 
                            <span>admin@warungtahu.com</span>
                        </li>
                    </ul>
                </div>

                {{-- KOLOM 2: MENU FAVORIT --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Menu Favorit</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Lontong Biasa</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Telur Spesial</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Tek</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary hover-text-white">Es Campur</a></li>
                    </ul>
                </div>

                {{-- KOLOM 3: LAYANAN --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Layanan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">Dine-in (Makan di Tempat)</li>
                        <li class="mb-2">Take Away (Bungkus)</li>
                        <li class="mb-2">Menerima Pesanan (Catering)</li>
                    </ul>
                </div>

                {{-- KOLOM 4: IKUTI KAMI & WA --}}
                <div class="col-lg-4 col-md-12">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Ikuti Kami</h6>
                    <p class="small mb-3">Dapatkan promo terbaru dan info menarik lewat sosial media kami.</p>
                    
                    <div class="d-flex gap-3 mb-4">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-whatsapp"></i></a>
                    </div>

                    <h6 class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.75rem;">PESAN CEPAT VIA WHATSAPP?</h6>
                    <a href="https://wa.me/6285795813531" target="_blank" class="btn btn-danger w-100 fw-bold rounded-pill py-2 shadow-sm" style="background-color: #e4002b; border: none;">
                        <i class="bi bi-whatsapp me-2"></i> CHAT SEKARANG
                    </a>
                </div>
            </div>
        </div>

        {{-- COPYRIGHT --}}
        <div class="py-3 text-center border-top border-secondary border-opacity-25" style="background-color: #111;">
            <small class="text-muted">&copy; 2025 Warung Tahu Lontong. All Rights Reserved.</small>
        </div>
    </footer>

    {{-- Tambahan CSS Khusus untuk Footer Hover Effect --}}
    <style>
        footer a.hover-text-white:hover {
            color: white !important;
            padding-left: 5px;
            transition: all 0.2s ease;
        }
    </style>

    {{-- Floating Cart --}}
    @if(session('cart') && count((array) session('cart')) > 0)
        @php 
            $totalQty = 0; $totalHarga = 0;
            foreach(session('cart') as $details) {
                $totalQty += $details['quantity'];
                $totalHarga += $details['price'] * $details['quantity'];
            }
        @endphp
        <a href="{{ route('cart') }}" class="floating-cart">
            <div class="d-flex align-items-center">
                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">{{ $totalQty }}</div>
                <div class="ms-3 d-flex flex-column text-start">
                    <span class="small opacity-75">Total</span><span class="fw-bold fs-6">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="fw-bold small">Lihat Keranjang <i class="bi bi-chevron-right ms-1"></i></div>
        </a>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-cat').click(function() {
                $('.btn-cat').removeClass('active');
                $(this).addClass('active');
                var categoryId = $(this).data('filter');
                if(categoryId == 'all') { $('.product-item').fadeIn('fast'); } 
                else { $('.product-item').hide(); $('.product-item[data-category="' + categoryId + '"]').fadeIn('fast'); }
            });
        });

        // FUNGSI HITUNG REAL-TIME
        function hitungTotal(id) {
            var modal = $('#modalOrder-' + id);
            var hargaDasar = parseInt(modal.find('.harga-dasar').val()) || 0;
            var qty = parseInt($('#qtyModal-' + id).val()) || 1;
            
            var totalAddon = 0;
            modal.find('.addon-check:checked').each(function() {
                totalAddon += parseInt($(this).data('price'));
            });

            var hargaPerItem = hargaDasar + totalAddon;
            var totalAkhir = hargaPerItem * qty;

            var formatRupiah = new Intl.NumberFormat('id-ID').format(totalAkhir);
            $('#btn-submit-' + id).text('Masuk Keranjang - Rp ' + formatRupiah);
        }

        function tambahModal(id) {
            let input = document.getElementById('qtyModal-' + id);
            let val = parseInt(input.value);
            input.value = isNaN(val) ? 1 : val + 1;
            hitungTotal(id); 
        }

        function kurangModal(id) {
            let input = document.getElementById('qtyModal-' + id);
            let val = parseInt(input.value);
            if (!isNaN(val) && val > 1) input.value = val - 1;
            hitungTotal(id);
        }
    </script>
</body>
</html>