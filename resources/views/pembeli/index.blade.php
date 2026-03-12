<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Tahu Lontong - Order</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font: Menggabungkan Nunito & Poppins agar sama persis dengan Admin --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TEMA BARU YANG DISERAGAMKAN DENGAN ADMIN --- */
        :root { 
            --primary-color: #DD3827; /* Merah Coral Ceria */
            --secondary-color: #FDE8E5; /* Pink Muda */
            --bg-light: #F9F9FB; 
            --text-dark: #2D3142; 
        }
        
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: var(--bg-light); 
            padding-top: 85px; 
            padding-bottom: 100px; 
            color: #4F5665;
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .navbar-brand {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }

        .navbar-kfc { background-color: white; box-shadow: 0 4px 24px rgba(0,0,0,0.03); padding: 12px 0; position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 1030; }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; }
        .nav-link { font-weight: 600; color: #64748B !important; margin: 0 10px; position: relative; font-family: 'Poppins', sans-serif;}
        .nav-link:hover, .nav-link.active { color: var(--primary-color) !important; }
        .nav-link.active::after { content: ''; position: absolute; width: 50%; height: 3px; background: var(--primary-color); bottom: -5px; left: 25%; border-radius: 10px; }
        
        .category-scroll { display: flex; overflow-x: auto; gap: 10px; padding-bottom: 10px; margin-bottom: 20px; scrollbar-width: none; }
        .category-scroll::-webkit-scrollbar { display: none; }
        
        .btn-cat { border: none; background: white; color: #64748B; border-radius: 50px; padding: 10px 24px; font-weight: 600; white-space: nowrap; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.03); font-family: 'Poppins', sans-serif;}
        .btn-cat:hover, .btn-cat.active { background-color: var(--primary-color); color: white; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(221, 56, 39, 0.2); }
        
        /* Banner diganti menjadi gradasi yang lebih ceria */
        .hero-banner { background: linear-gradient(135deg, var(--primary-color), #FF7A68); border-radius: 24px; padding: 40px 20px; text-align: center; color: white; margin-bottom: 30px; box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2); }
        
        .service-option { background: white; border-radius: 20px; padding: 15px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.03); transition: all 0.3s; border: 2px solid transparent; cursor: pointer; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #64748B; text-decoration: none; }
        .service-option:hover { transform: translateY(-5px); border-color: var(--secondary-color); box-shadow: 0 12px 25px rgba(0,0,0,0.05);}
        .active-service { background-color: var(--secondary-color) !important; border: 2px solid var(--primary-color) !important; color: var(--primary-color) !important; box-shadow: 0 8px 20px rgba(221, 56, 39, 0.1) !important; font-weight: bold; }
        
        .product-card { border: none; border-radius: 20px; background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.04); transition: all 0.3s ease; height: 100%; overflow: hidden; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); }
        .product-img-wrapper { height: 160px; overflow: hidden; position: relative; background: #eee; }
        .product-img { width: 100%; height: 100%; object-fit: cover; }
        
        .btn-add { background-color: var(--primary-color); color: white; border: none; border-radius: 50px; padding: 8px 0; font-weight: 600; width: 100%; transition: 0.3s; font-family: 'Poppins', sans-serif;}
        .btn-add:hover { background-color: #C02E1F; color: white; transform: scale(1.02);}
        
        .modal-content { border-radius: 24px; border: none; overflow: hidden; }
        .qty-control { background: var(--bg-light); border-radius: 50px; padding: 5px; display: flex; align-items: center; }
        .btn-qty { width: 32px; height: 32px; border-radius: 50%; border: none; background: white; color: var(--text-dark); font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.2s;}
        .btn-qty:hover { background: var(--primary-color); color: white;}
        .input-qty { width: 40px; border: none; background: transparent; text-align: center; font-weight: bold; color: var(--text-dark);}
        
        .floating-cart { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: var(--text-dark); color: white; padding: 15px 25px; border-radius: 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 15px 35px rgba(45, 49, 66, 0.3); z-index: 1050; text-decoration: none; transition: 0.3s;}
        .floating-cart:hover { transform: translateX(-50%) translateY(-5px); color: white; background: #1e212d; }

        /* Badge Tambahan */
        .custom-badge { background-color: var(--secondary-color); color: var(--primary-color); border: 1px solid rgba(221,56,39,0.2); }
    </style>
</head>
<body id="beranda">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-kfc fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('template/img/tahu.png') }}" width="45" height="45" class="me-2 rounded-circle border border-2 border-white shadow-sm">
                <span class="ms-1" style="color: var(--primary-color);">Tahu Lontong</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#menu-area">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://maps.app.goo.gl/QXPS4XWyi1L5ofAo7" target="_blank">Lokasi</a></li>
                    @auth <li class="nav-item"><a class="nav-link" href="{{ route('pembeli.riwayat') }}">Riwayat</a></li> @endauth
                </ul>
                <div class="d-flex align-items-center mt-3 mt-lg-0">
                    <a href="{{ route('cart') }}" class="me-4 text-dark position-relative fs-5 transition">
                        <i class="bi bi-bag-fill" style="color: var(--text-dark);"></i>
                        @if(session('cart')) <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: var(--primary-color); font-size: 0.65rem; border: 2px solid white;">{{ count((array) session('cart')) }}</span> @endif
                    </a>
                    @auth
                        <div class="dropdown">
                            <a class="btn rounded-pill fw-bold px-4" style="background-color: var(--secondary-color); color: var(--primary-color);" href="#" role="button" data-bs-toggle="dropdown">{{ explode(' ', Auth::user()->name)[0] }}</a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3" style="border-radius: 16px;">
                                @if(in_array(Auth::user()->usertype, ['admin', 'kasir'])) <li><a class="dropdown-item py-2 fw-semibold" href="/home"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li> @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><form action="{{ route('logout') }}" method="POST">@csrf <button class="dropdown-item py-2 text-danger fw-semibold"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form></li>
                            </ul>
                        </div>
                    @else
                        <a href="/login" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background: var(--primary-color); border:none;">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero-banner">
            <h2 class="fw-bold mb-2 display-6">Lapar? Pesan Sekarang!</h2>
            <p class="mb-0 fs-5" style="opacity: 0.9;">Nikmati Tahu Lontong Legendaris</p>
        </div>

        @if(session('success'))
            <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background-color: #E8F5E9; color: #2E7D32;">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i> <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Layanan --}}
        <div class="mb-5">
            <h5 class="fw-bold mb-3 ps-2" style="color: var(--text-dark);">Mau makan gimana?</h5>
            @php $layanan = session('jenis_pesanan', 'takeaway'); @endphp
            <div class="row g-3">
                <div class="col-4"><a href="{{ route('set.layanan', 'takeaway') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'takeaway' ? 'active-service' : '' }}"><i class="bi bi-bag-check-fill fs-3 mb-2"></i><div class="fw-bold small">Takeaway</div></div></a></div>
                <div class="col-4"><a href="{{ route('set.layanan', 'dine_in') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'dine_in' ? 'active-service' : '' }}"><i class="bi bi-shop fs-3 mb-2"></i><div class="fw-bold small">Dine In</div></div></a></div>
                <div class="col-4"><a href="{{ route('set.layanan', 'delivery') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'delivery' ? 'active-service' : '' }}"><i class="bi bi-box-seam-fill fs-3 mb-2"></i><div class="fw-bold small">Delivery</div></div></a></div>
            </div>
        </div>

        {{-- Menu Area --}}
        <div id="menu-area" class="pt-2">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0" style="color: var(--text-dark);">Menu Pilihan</h4>
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
                    <div class="card product-card h-100 {{ $isNonAktif ? 'bg-light border-0' : '' }}" style="{{ $isNonAktif ? 'opacity: 0.7;' : '' }}">
                        
                        <div class="product-img-wrapper position-relative">
                            @if($p->gambar) 
                                <img src="{{ asset('img/produk/'.$p->gambar) }}" class="product-img" style="{{ $isNonAktif ? 'filter: grayscale(100%);' : '' }}"> 
                            @else 
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted"><i class="bi bi-image fs-1"></i></div> 
                            @endif

                            @if($isNonAktif)
                                <div class="position-absolute top-50 start-50 translate-middle badge bg-dark px-3 py-2 text-uppercase shadow rounded-pill font-monospace">
                                    {{ $p->status == 'non-aktif' ? 'Tidak Tersedia' : 'Habis' }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <div class="mb-2">
                                <h6 class="fw-bold mb-1 text-truncate {{ $isNonAktif ? 'text-muted' : '' }}" style="color: var(--text-dark);">{{ $p->nama_produk }}</h6>
                                <div class="text-muted small mb-2 fw-semibold" style="font-size: 0.75rem;">{{ $p->kategori?->nama_kategori }}</div>
                                
                                <div class="{{ $isNonAktif ? 'text-muted text-decoration-line-through' : 'fw-bold' }}" style="color: {{ $isNonAktif ? '' : 'var(--primary-color)' }}; font-size: 1.1rem;">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-3 border-top border-light">
                                @if($isNonAktif)
                                    <button type="button" class="btn w-100 btn-sm rounded-pill fw-bold text-white" disabled style="background-color: #CBD5E1; border:none; padding: 8px 0;">
                                        <i class="bi bi-x-circle me-1"></i> Kosong
                                    </button>
                                @else
                                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalOrder-{{ $p->id }}">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL PESANAN --}}
                @if(!$isNonAktif)
                <div class="modal fade" id="modalOrder-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h4 class="modal-title fw-bold" style="color: var(--text-dark);">{{ $p->nama_produk }}</h4>
                                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" style="box-shadow: none;"></button>
                            </div>
                            <form action="{{ route('add.to.cart', $p->id) }}" method="GET">
                                <div class="modal-body px-4 pb-4 pt-3">
                                    
                                    {{-- ... BAGIAN LOGIKA DETEKSI KATEGORI (TETAP SAMA) ... --}}
                                    @php 
                                        $namaKategori = strtolower($p->kategori->nama_kategori ?? '');
                                        
                                        $isDessert = str_contains($namaKategori, 'dessert') || str_contains($namaKategori, 'campur');
                                        $isMinuman = !$isDessert && (str_contains($namaKategori, 'minuman') || str_contains($namaKategori, 'drink'));
                                        $isSnack   = str_contains($namaKategori, 'snack') || str_contains($namaKategori, 'cemilan');
                                    @endphp

                                    @if($isDessert)
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                        {{-- (Kode Form Dessert Tetap Sama, Hanya Sesuaikan Class Jika Perlu) --}}
                                        <div class="mb-3">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Pilihan Es</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Es Normal" checked><label class="form-check-label small fw-semibold">Normal Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Extra Es"><label class="form-check-label small fw-semibold">Extra Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Tanpa Es"><label class="form-check-label small fw-semibold">Tanpa Es</label></div>
                                            </div>
                                        </div>
                                        {{-- (Susu, Toping, dll. Biarkan Sesuai Kode Asli Anda) --}}
                                        <div class="mb-3">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Opsi Susu</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_susu" value="Pakai Susu" checked><label class="form-check-label small fw-semibold">Pakai Susu</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_susu" value="Tanpa Susu"><label class="form-check-label small fw-semibold">Tanpa Susu</label></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Tambah Buah <span class="badge custom-badge ms-1 rounded-pill">+Rp 2.000</span></label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach(['Semangka', 'Melon', 'Nanas', 'Nangka', 'Blewah'] as $buah)
                                                <div class="form-check border px-3 py-2 rounded-pill">
                                                    <input class="form-check-input addon-check" type="checkbox" name="buah[]" value="{{ $buah }}" data-price="2000" onchange="hitungTotal({{ $p->id }})">
                                                    <label class="form-check-label small fw-semibold">{{ $buah }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Extra Lainnya</label>
                                            <div class="list-group border-0">
                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border rounded-4 mb-2 py-3 px-3 shadow-sm">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <input class="form-check-input flex-shrink-0 addon-check mt-0" type="checkbox" name="extra_jelly" value="Extra Jelly" data-price="2000" onchange="hitungTotal({{ $p->id }})">
                                                        <span class="fw-semibold">Extra Jelly/Cincau</span>
                                                    </div>
                                                    <span class="small fw-bold" style="color: var(--primary-color);">+Rp 2.000</span>
                                                </label>
                                            </div>
                                        </div>

                                    @elseif($isMinuman)
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                        <div class="mb-4">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Pilihan Es</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Es Normal" checked><label class="form-check-label small fw-semibold">Normal Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Sedikit Es"><label class="form-check-label small fw-semibold">Sedikit Es</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="radio" name="opsi_es" value="Hangat"><label class="form-check-label small fw-semibold">Hangat (No Es)</label></div>
                                            </div>
                                        </div>

                                    @elseif($isSnack)
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                    @else
                                        <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                        <div class="mb-3">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Level Pedas</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0 text-danger rounded-start-pill ps-3"><i class="bi bi-fire"></i></span>
                                                <input type="number" name="cabe" class="form-control border-start-0 rounded-end-pill py-2" placeholder="0 (Tidak Pedas)" min="0" max="20">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Request Toping</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="checkbox" name="tanpa_bawang" id="noBawang{{ $p->id }}"><label class="form-check-label small fw-semibold" for="noBawang{{ $p->id }}">No Bawang</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="checkbox" name="tanpa_timun" id="noTimun{{ $p->id }}"><label class="form-check-label small fw-semibold" for="noTimun{{ $p->id }}">No Timun</label></div>
                                                <div class="form-check border px-3 py-2 rounded-pill"><input class="form-check-input" type="checkbox" name="tanpa_seledri" id="noSeledri{{ $p->id }}"><label class="form-check-label small fw-semibold" for="noSeledri{{ $p->id }}">No Seledri</label></div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="fw-bold small mb-2" style="color: var(--text-dark);">Ekstra Tambahan</label>
                                            <div class="list-group border-0 gap-2">
                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border rounded-4 py-3 px-3 shadow-sm">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <input class="form-check-input flex-shrink-0 addon-check mt-0" type="checkbox" name="tambah_telur" value="Telur Dadar" data-price="4000" onchange="hitungTotal({{ $p->id }})">
                                                        <span class="fw-semibold">Tambah Telur Dadar</span>
                                                    </div>
                                                    <span class="small fw-bold" style="color: var(--primary-color);">+Rp 4.000</span>
                                                </label>

                                                <label class="list-group-item d-flex justify-content-between align-items-center gap-2 border rounded-4 py-3 px-3 shadow-sm">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <input class="form-check-input flex-shrink-0 addon-check mt-0" type="checkbox" name="extra_lontong" value="Extra Lontong" data-price="3000" onchange="hitungTotal({{ $p->id }})">
                                                        <span class="fw-semibold">Extra Lontong</span>
                                                    </div>
                                                    <span class="small fw-bold" style="color: var(--primary-color);">+Rp 3.000</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- BAGIAN BAWAH MODAL (Hitung & Simpan) --}}
                                    <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                        <div class="qty-control shadow-sm">
                                            <button type="button" class="btn-qty" onclick="kurangModal({{ $p->id }})"><i class="bi bi-dash"></i></button>
                                            <input type="number" name="quantity" id="qtyModal-{{ $p->id }}" class="input-qty fs-5" value="1" min="1" readonly>
                                            <button type="button" class="btn-qty" onclick="tambahModal({{ $p->id }})"><i class="bi bi-plus"></i></button>
                                        </div>
                                        <button type="submit" id="btn-submit-{{ $p->id }}" class="btn text-white rounded-pill px-4 py-3 fw-bold shadow-sm" style="background: var(--primary-color);">
                                            Tambah - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </button>
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
    <footer style="background-color: var(--text-dark); color: #b0b0b0; font-family: 'Nunito', sans-serif;">
        
        {{-- Garis Coral di Atas Footer --}}
        <div style="height: 6px; background-color: var(--primary-color); width: 100%;"></div>

        <div class="container py-5">
            {{-- Isi Footer Tetap Sama, Hanya Penyesuaian Warna --}}
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-family: 'Poppins', sans-serif;">
                        <img src="{{ asset('template/img/tahu.png') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle bg-white p-1">
                        WARUNG TAHU
                    </h5>
                    <p class="small mb-4">Menyajikan Tahu Lontong & Tahu Tek dengan resep bumbu petis legendaris sejak 2010.</p>
                    <ul class="list-unstyled small">
                        <li class="mb-2 d-flex align-items-start"><i class="bi bi-geo-alt-fill me-2 mt-1" style="color: var(--primary-color);"></i> <span>Jl. Sukorejo Indah No. 314, Katang</span></li>
                        <li class="mb-2 d-flex align-items-center"><i class="bi bi-clock-fill me-2" style="color: var(--primary-color);"></i> <span>Buka: 10.00 - 16.00 WIB</span></li>
                    </ul>
                </div>
                
                {{-- (Kolom Lainnya Disingkat untuk Kerapihan, Kode Asli Anda Tidak Ada Masalah Disini) --}}
                <div class="col-lg-4 col-md-12 ms-auto">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small" style="font-family: 'Poppins', sans-serif;">Pesan Cepat Via Whatsapp?</h6>
                    <a href="https://wa.me/6285795813531" target="_blank" class="btn w-100 fw-bold rounded-pill py-3 shadow-sm text-white" style="background-color: var(--primary-color); border: none; font-family: 'Poppins', sans-serif;">
                        <i class="bi bi-whatsapp me-2 fs-5"></i> CHAT SEKARANG
                    </a>
                </div>
            </div>
        </div>
    </footer>

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
                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; color: var(--primary-color) !important;">{{ $totalQty }}</div>
                <div class="ms-3 d-flex flex-column text-start">
                    <span class="small" style="opacity: 0.8;">Total Keranjang</span>
                    <span class="fw-bold fs-6" style="font-family: 'Poppins', sans-serif;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="fw-bold small" style="font-family: 'Poppins', sans-serif;">Bayar <i class="bi bi-chevron-right ms-1"></i></div>
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
            $('#btn-submit-' + id).text('Tambah - Rp ' + formatRupiah);
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