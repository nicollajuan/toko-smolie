<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smolie Gift - Pusat Custom Souvenir</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TEMA --- */
        :root { 
            --primary-color: #DD3827; 
            --secondary-color: #FDE8E5; 
            --bg-light: #F9F9FB; 
            --text-dark: #2D3142; 
        }
        
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: var(--bg-light); 
            padding-top: 80px; 
            color: #4F5665;
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .navbar-brand {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }

        /* NAVBAR */
        .navbar-kfc { background-color: white; box-shadow: 0 4px 24px rgba(0,0,0,0.03); padding: 12px 0; position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 1030; }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; }
        .nav-link { font-weight: 600; color: #64748B !important; margin: 0 10px; position: relative; font-family: 'Poppins', sans-serif;}
        .nav-link:hover, .nav-link.active { color: var(--primary-color) !important; }
        @media (min-width: 992px) {
            .nav-link.active::after { content: ''; position: absolute; width: 50%; height: 3px; background: var(--primary-color); bottom: -5px; left: 25%; border-radius: 10px; }
        }
        
        /* BANNER */
        .hero-banner { 
            background: linear-gradient(135deg, var(--primary-color), #FF7A68); 
            border-radius: 20px; 
            padding: 30px 15px;
            text-align: center; 
            color: white; 
            margin-bottom: 25px; 
            box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2); 
        }
        @media (min-width: 768px) { .hero-banner { padding: 40px 20px; border-radius: 24px; margin-bottom: 30px; } }
        
        /* KARTU PENGIRIMAN */
        .service-option { 
            background: white; border-radius: 16px; padding: 15px 10px; text-align: center; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; border: 2px solid transparent; 
            cursor: pointer; height: 100%; display: flex; flex-direction: column; justify-content: center; 
            align-items: center; color: #64748B; text-decoration: none; 
        }
        .service-option:hover { transform: translateY(-3px); border-color: var(--secondary-color); box-shadow: 0 8px 20px rgba(0,0,0,0.05);}
        .active-service { background-color: var(--secondary-color) !important; border: 2px solid var(--primary-color) !important; color: var(--primary-color) !important; box-shadow: 0 8px 20px rgba(221, 56, 39, 0.1) !important; font-weight: bold; }
        
        /* FILTER HORIZONTAL SCROLL KHUSUS MOBILE */
        .category-scroll { 
            display: flex; overflow-x: auto; gap: 8px; padding-bottom: 10px; margin-bottom: 20px; 
            scrollbar-width: none; -ms-overflow-style: none;
            white-space: nowrap;
        }
        .category-scroll::-webkit-scrollbar { display: none; } 
        
        .btn-cat { border: none; background: white; color: #64748B; border-radius: 50px; padding: 8px 20px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.03); font-family: 'Poppins', sans-serif;}
        .btn-cat:hover, .btn-cat.active { background-color: var(--primary-color); color: white; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(221, 56, 39, 0.2); }
        
        /* KARTU PRODUK */
        .product-card { border: none; border-radius: 16px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: all 0.3s ease; height: 100%; overflow: hidden; display: flex; flex-direction: column;}
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .product-img-wrapper { height: 120px; overflow: hidden; position: relative; background: #eee; }
        @media (min-width: 768px) { .product-img-wrapper { height: 160px; } }
        .product-img { width: 100%; height: 100%; object-fit: cover; }
        
        /* TOMBOL PESAN */
        .btn-add { background-color: var(--primary-color); color: white; border: none; border-radius: 50px; padding: 6px 0; font-weight: 600; font-size: 0.9rem; width: 100%; transition: all 0.2s ease-in-out; font-family: 'Poppins', sans-serif;}
        .btn-add:hover { background-color: #C02E1F; box-shadow: 0 4px 12px rgba(221, 56, 39, 0.3); transform: translateY(-1px); color: white;}
        .btn-add:active { transform: translateY(1px); }
        
        /* MODAL */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; }
        .qty-control { background: var(--bg-light); border-radius: 50px; padding: 5px; display: flex; align-items: center; }
        .btn-qty { width: 32px; height: 32px; border-radius: 50%; border: none; background: white; color: var(--text-dark); font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.2s;}
        .btn-qty:hover { background: var(--primary-color); color: white;}
        .input-qty { width: 40px; border: none; background: transparent; text-align: center; font-weight: bold; color: var(--text-dark);}
        .btn-check-custom + .btn { border-radius: 50px; font-size: 0.85rem; padding: 5px 12px; color: #64748B; border-color: #CBD5E1; background-color: white; transition: all 0.2s ease;}
        .btn-check-custom:checked + .btn { background-color: #64748B; color: white; border-color: #64748B; }
        
        /* FLOATING CART */
        .floating-cart { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: var(--text-dark); color: white; padding: 12px 20px; border-radius: 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(45, 49, 66, 0.3); z-index: 1050; text-decoration: none; transition: 0.3s;}
        .floating-cart:hover { transform: translateX(-50%) translateY(-3px); color: white; background: #1e212d; }
    </style>
</head>
<body id="beranda">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-kfc fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('template/img/smolie.jpg') }}" width="40" height="40" class="me-2 rounded-circle border border-2 border-white shadow-sm">
                <span class="ms-1" style="color: var(--primary-color);">Smolie Gift</span>
            </a>
            
            <div class="d-flex align-items-center d-lg-none">
                <a href="{{ route('cart') }}" class="me-3 text-dark position-relative">
                    <i class="bi bi-bag-fill fs-5"></i>
                    @if(session('cart')) <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; border: 2px solid white;">{{ count((array) session('cart')) }}</span> @endif
                </a>
                <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><i class="bi bi-list fs-1 text-dark"></i></button>
            </div>

            <div class="collapse navbar-collapse text-center text-lg-start mt-3 mt-lg-0" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0">
    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}#beranda">Beranda</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#menu-area">Katalog</a></li>
    
    {{-- Menu Baru: Lokasi Pop-up --}}
    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalLokasi">Lokasi</a>
    </li>
    
    {{-- Menu Kontak Pop-up --}}
    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalKontak">Kontak</a>
    </li>
    @auth <li class="nav-item"><a class="nav-link" href="{{ route('pembeli.riwayat') }}">Riwayat</a></li> @endauth
</ul>
                <div class="d-none d-lg-flex align-items-center">
                    <a href="{{ route('cart') }}" class="me-4 text-dark position-relative transition">
                        <i class="bi bi-bag-fill fs-5"></i>
                        @if(session('cart')) <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; border: 2px solid white;">{{ count((array) session('cart')) }}</span> @endif
                    </a>
                    @auth
                        <div class="dropdown">
                            <a class="btn btn-sm rounded-pill fw-bold px-3 py-2 d-flex align-items-center" style="background-color: var(--secondary-color); color: var(--primary-color);" href="#" data-bs-toggle="dropdown">
    {{ explode(' ', Auth::user()->name)[0] }}

    {{-- Logika Warna Lencana Berdasarkan Level --}}
    @php
        $lvl = Auth::user()->level_member ?? 'Bronze';
        $bgBadge = 'bg-secondary'; // Default Bronze
        if($lvl == 'Silver') $bgBadge = 'bg-info text-dark';
        if($lvl == 'Gold') $bgBadge = 'bg-warning text-dark';
        if($lvl == 'Platinum') $bgBadge = 'bg-dark text-white';
    @endphp

    <span class="badge rounded-pill ms-2 {{ $bgBadge }}" style="font-size: 0.65rem;">{{ $lvl }}</span>
</a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 12px;">
                                @if(in_array(Auth::user()->usertype, ['admin', 'kasir'])) 
                                    <li><a class="dropdown-item py-2 small fw-semibold" href="/home"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li> 
                                @endif
                                {{-- PERUBAHAN: Tombol Logout memanggil Modal --}}
                                <li>
                                    <button type="button" class="dropdown-item py-2 small text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLogoutKonfirmasi">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="/login" class="btn btn-sm text-white rounded-pill px-4 py-2 fw-bold shadow-sm" style="background: var(--primary-color);">Login</a>
                    @endauth
                </div>
                
                @guest
                <div class="d-lg-none mt-2">
                    <a href="/login" class="btn text-white w-100 rounded-pill fw-bold" style="background: var(--primary-color);">Login ke Akun</a>
                </div>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        {{-- BANNER HERO --}}
        <div class="hero-banner">
            <h2 class="fw-bold mb-1 fs-3 fs-md-2">Cari Souvenir Unik & Cantik?</h2>
            <p class="mb-0 fs-6" style="opacity: 0.9;">Temukan beragam custom souvenir di Smolie Gift!</p>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center py-2 px-3 small" style="background-color: #E8F5E9; color: #2E7D32;">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i> <span>{{ session('success') }}</span>
                <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center py-2 px-3 small" style="background-color: #FFEBEE; color: #C62828;">
                <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i> <span>{{ session('error') }}</span>
                <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- LAYANAN PENGAMBILAN --}}
        <div class="mb-4">
            <h6 class="fw-bold mb-2 ps-1" style="color: var(--text-dark);">Metode Pengambilan Pesanan</h6>
            @php $layanan = session('jenis_pesanan', 'takeaway'); @endphp
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <a href="{{ route('set.layanan', 'takeaway') }}" class="text-decoration-none">
                        <div class="service-option {{ $layanan == 'takeaway' ? 'active-service' : '' }} flex-row flex-md-column justify-content-start justify-content-md-center px-3 py-2 py-md-3">
                            <i class="bi bi-shop fs-4 me-3 me-md-0 mb-md-2"></i>
                            <div class="fw-bold small m-0">Ambil di Toko (Pickup)</div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('set.layanan', 'delivery') }}" class="text-decoration-none">
                        <div class="service-option {{ $layanan == 'delivery' ? 'active-service' : '' }} flex-row flex-md-column justify-content-start justify-content-md-center px-3 py-2 py-md-3">
                            <i class="bi bi-box-seam-fill fs-4 me-3 me-md-0 mb-md-2"></i>
                            <div class="fw-bold small m-0">Kirim via Ekspedisi</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- AREA KATALOG --}}
        <div id="menu-area" class="pt-2">
            <h5 class="fw-bold mb-2" style="color: var(--text-dark);">Katalog Produk</h5>

            <div class="category-scroll">
                <button class="btn btn-cat active" data-filter="all">Semua Kategori</button>
                @foreach($kategori as $k)
                    <button class="btn btn-cat" data-filter="{{ $k->id }}">{{ $k->nama_kategori }}</button>
                @endforeach
            </div>
            
            <div class="row g-3 product-container">
                @foreach($produk as $p)
                @php $isNonAktif = ($p->status == 'non-aktif') || ($p->stock <= 0); @endphp

                <div class="col-6 col-md-4 col-lg-3 product-item" data-category="{{ $p->kategori_id }}">
                    <div class="card product-card {{ $isNonAktif ? 'bg-light border-0' : '' }}" style="{{ $isNonAktif ? 'opacity: 0.7;' : '' }}">
                        
                        <div class="product-img-wrapper position-relative">
                            @if($p->gambar) 
                                <img src="{{ asset('img/produk/'.$p->gambar) }}" alt="{{ $p->nama_produk }}" class="product-img" loading="lazy" style="{{ $isNonAktif ? 'filter: grayscale(100%);' : '' }}">
                            @else 
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted"><i class="bi bi-image fs-1"></i></div> 
                            @endif

                            @if($isNonAktif)
                                <div class="position-absolute top-50 start-50 translate-middle badge bg-dark px-2 py-1 text-uppercase shadow rounded-pill font-monospace" style="font-size: 0.65rem;">
                                    {{ $p->status == 'non-aktif' ? 'Tidak Tersedia' : 'Habis' }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-2 p-md-3 flex-grow-1">
                            <div class="mb-auto">
                                <h6 class="fw-bold mb-0 text-truncate {{ $isNonAktif ? 'text-muted' : '' }}" style="color: var(--text-dark); font-size: 0.95rem;">{{ $p->nama_produk }}</h6>
                                <div class="text-muted small mb-1 fw-semibold" style="font-size: 0.7rem;">{{ $p->kategori?->nama_kategori }}</div>
                                <div class="{{ $isNonAktif ? 'text-muted text-decoration-line-through' : 'fw-bold' }}" style="color: {{ $isNonAktif ? '' : 'var(--primary-color)' }}; font-size: 0.95rem;">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                @if($p->stock <= 0)
                                    <button type="button" class="btn w-100 btn-sm rounded-pill fw-bold text-white shadow-none" disabled style="background-color: #dc3545; opacity: 0.6; border:none;">Habis</button>
                                @elseif(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                                    <button type="button" class="btn w-100 btn-sm rounded-pill fw-bold text-white shadow-none" disabled style="background-color: #94a3b8; border:none;">Preview</button>
                                @else
                                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalOrder-{{ $p->id }}">Pesan</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$isNonAktif)
                <div class="modal fade" id="modalOrder-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0 pt-3 px-3">
                                <h5 class="modal-title fw-bold" style="color: var(--text-dark);">{{ $p->nama_produk }}</h5>
                                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" style="box-shadow: none;"></button>
                            </div>
                            
                            <form action="{{ route('add.to.cart', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body px-3 pb-3 pt-2">
                                    <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark mb-1">Varian / Motif</label>
                                        <select class="form-select form-select-sm border-secondary rounded-3" name="warna" id="pilihan-varian-{{ $p->id }}" onchange="cekPilihanCustom({{ $p->id }})">
                                            <option value="">Pilih varian...</option>
                                            <option value="Pastel">Warna Pastel</option>
                                            <option value="Monokrom">Monokrom</option>
                                            <option value="Emas">Aksen Emas</option>
                                            <option value="Campur">Random</option>
                                            <option value="custom">Custom Desain Sendiri</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark mb-1">Ketebalan Kertas</label>
                                        <div class="d-flex flex-wrap gap-1">
                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas1-{{ $p->id }}" value="standar" data-price="0" checked onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-2 py-1 fw-semibold" for="kertas1-{{ $p->id }}">Standar</label>

                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas2-{{ $p->id }}" value="tebal" data-price="1000" onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-2 py-1 fw-semibold" for="kertas2-{{ $p->id }}">Tebal (+1k)</label>

                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas3-{{ $p->id }}" value="ekstra" data-price="2500" onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-2 py-1 fw-semibold" for="kertas3-{{ $p->id }}">Ekstra (+2.5k)</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark mb-1">Tambahan</label>
                                        <div class="form-check p-2 border rounded-3 mb-1 d-flex justify-content-between align-items-center bg-light">
                                            <div>
                                                <input class="form-check-input ms-0 me-2 addon-check" type="checkbox" name="ekstra[]" value="Sablon" id="ext1-{{ $p->id }}" data-price="500" onchange="hitungTotal({{ $p->id }})">
                                                <label class="form-check-label small fw-semibold" for="ext1-{{ $p->id }}">Sablon Inisial</label>
                                            </div>
                                            <span class="text-danger fw-bold small">+500</span>
                                        </div>
                                        <div class="form-check p-2 border rounded-3 d-flex justify-content-between align-items-center bg-light">
                                            <div>
                                                <input class="form-check-input ms-0 me-2 addon-check" type="checkbox" name="ekstra[]" value="Kartu Ucapan" id="ext2-{{ $p->id }}" data-price="300" onchange="hitungTotal({{ $p->id }})">
                                                <label class="form-check-label small fw-semibold" for="ext2-{{ $p->id }}">Thanks Card</label>
                                            </div>
                                            <span class="text-danger fw-bold small">+300</span>
                                        </div>
                                    </div>

                                    <div id="area-custom-{{ $p->id }}" style="display: none;">
                                        <div class="mb-3 p-2 rounded-3" style="background-color: #FDE8E5; border: 1px dashed #DD3827;">
                                            <label class="form-label fw-bold small mb-1" style="color: #DD3827;"><i class="bi bi-palette-fill me-1"></i>Desain Custom</label>
                                            <input class="form-control form-control-sm mb-2 border-white" type="file" name="file_desain" accept="image/png, image/jpeg, application/pdf">
                                            <textarea class="form-control form-control-sm border-white" name="catatan_desain" rows="2" placeholder="Catatan desain..."></textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div class="qty-control shadow-sm">
                                            <button type="button" class="btn-qty" onclick="kurangModal({{ $p->id }})"><i class="bi bi-dash"></i></button>
                                            <input type="number" name="qty" id="qtyModal-{{ $p->id }}" class="input-qty" value="1" min="1" readonly>
                                            <button type="button" class="btn-qty" onclick="tambahModal({{ $p->id }})"><i class="bi bi-plus"></i></button>
                                        </div>
                                        <button type="submit" id="btn-submit-{{ $p->id }}" class="btn btn-sm text-white rounded-pill px-3 py-2 fw-bold" style="background: var(--primary-color);">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
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
    
    @include('layouts.footer')

    {{-- Floating Cart --}}
    @if(session('cart') && count((array) session('cart')) > 0)
        @php $totalQty = 0; $totalHarga = 0; foreach(session('cart') as $d) { $totalQty += $d['quantity']; $totalHarga += $d['price'] * $d['quantity']; } @endphp
        <a href="{{ route('cart') }}" class="floating-cart" style="bottom: {{ Auth::check() ? '80px' : '20px' }};"> 
            <div class="d-flex align-items-center">
                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; color: var(--primary-color) !important; font-size: 0.9rem;">{{ $totalQty }}</div>
                <div class="ms-2 d-flex flex-column text-start">
                    <span class="fw-bold text-white" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="fw-bold text-white" style="font-size: 0.85rem;">Order <i class="bi bi-chevron-right ms-1"></i></div>
        </a>
    @endif

    {{-- TAMBAHAN: MODAL KONFIRMASI LOGOUT UNTUK PEMBELI --}}
    <div class="modal fade" id="modalLogoutKonfirmasi" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-danger rounded-circle" style="width: 70px; height: 70px;">
                            <i class="bi bi-box-arrow-right fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark" id="modalLogoutLabel">Yakin ingin keluar?</h5>
                    <p class="text-muted mb-4 small">Sesi Anda saat ini akan diakhiri.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm" style="background-color: #DD3827; border: none; border-radius: 12px;">
                                Ya, Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cekPilihanCustom(id) {
            var valPilihan = $('#pilihan-varian-' + id).val();
            if(valPilihan === 'custom') { $('#area-custom-' + id).slideDown('fast'); } else { $('#area-custom-' + id).slideUp('fast'); }
        }
        function hitungTotal(id) {
            var m = $('#modalOrder-' + id);
            var h = parseInt(m.find('.harga-dasar').val()) || 0;
            var q = parseInt($('#qtyModal-' + id).val()) || 1;
            var k = parseInt(m.find('.kemasan-radio:checked').data('price')) || 0;
            var a = 0;
            m.find('.addon-check:checked').each(function() { a += parseInt($(this).data('price')); });
            $('#btn-submit-' + id).text('Rp ' + new Intl.NumberFormat('id-ID').format((h + k + a) * q));
        }
        function tambahModal(id) { let i = document.getElementById('qtyModal-'+id); i.value = parseInt(i.value) + 1; hitungTotal(id); }
        function kurangModal(id) { let i = document.getElementById('qtyModal-'+id); if(parseInt(i.value)>1) i.value = parseInt(i.value)-1; hitungTotal(id); }

        $(document).ready(function() {
            $('.btn-cat').click(function() {
                $('.btn-cat').removeClass('active'); $(this).addClass('active');
                var c = $(this).data('filter');
                if(c == 'all') { $('.product-item').fadeIn('fast'); } else { $('.product-item').hide(); $('.product-item[data-category="'+c+'"]').fadeIn('fast'); }
            });
            
            // Pindahkan modal logout ke body
            var modalLogout = document.getElementById('modalLogoutKonfirmasi');
            if(modalLogout) {
                document.body.appendChild(modalLogout);
            }
        });
    </script>
    {{-- TAMBAHAN: MODAL LOKASI GOOGLE MAPS --}}
    <div class="modal fade" id="modalLokasi" tabindex="-1" aria-labelledby="modalLokasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0 pt-3 px-4">
                    <h5 class="modal-title fw-bold" id="modalLokasiLabel" style="color: var(--text-dark);">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi Smolie Gift
                    </h5>
                    <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" style="box-shadow: none;"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Kotak Peta (Rasio 16:9 agar responsif di HP) --}}
                    {{-- Kotak Peta (Rasio 16:9 agar responsif di HP) --}}
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm" style="background-color: #eee;">
                        
    {{-- Contoh Hasil Paste yang Benar: --}}
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126646.20960533252!2d112.63028212154378!3d-7.27561413813898!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac47d%3A0x3039d80b220cb27!2sSurabaya%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1718167890123!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        
</div>
                    <div class="text-center mt-3">
                        <p class="fw-semibold text-muted mb-0 small">
                            <i class="bi bi-building me-1"></i> Jl. Raya No. 123 Surabaya
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- TAMBAHAN: SCRIPT UNTUK PINDAH GARIS MERAH NAVBAR --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil semua tombol menu di navbar
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // 1. Hapus garis merah (class 'active') dari semua menu
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    
                    // 2. Berikan garis merah (class 'active') ke menu yang baru saja diklik
                    this.classList.add('active');
                });
            });
        });
    </script>
    {{-- TAMBAHAN: MODAL KONTAK WHATSAPP --}}
    <div class="modal fade" id="modalKontak" tabindex="-1" aria-labelledby="modalKontakLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0 pt-3 px-4">
                    <h5 class="modal-title fw-bold" id="modalKontakLabel" style="color: var(--text-dark);">
                        <i class="bi bi-headset text-success me-2"></i>Hubungi Kami
                    </h5>
                    <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" style="box-shadow: none;"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width: 70px; height: 70px;">
                            <i class="bi bi-whatsapp text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">Butuh Bantuan?</h6>
                    <p class="text-muted small mb-4">Punya pertanyaan seputar custom souvenir atau pesananmu? Admin Smolie Gift siap membantu!</p>
                    
                    {{-- Tombol Lanjut ke WhatsApp --}}
                    <a href="https://wa.me/{{ $site_whatsapp ?? '6281515490908' }}" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold w-100 shadow-sm" style="background-color: #25D366; border: none; transition: 0.2s;">
                        <i class="bi bi-chat-dots-fill me-2"></i> Chat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>