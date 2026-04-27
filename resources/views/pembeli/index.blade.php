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
    
    {{-- Font: Menggabungkan Nunito & Poppins agar sama persis dengan Admin --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TEMA BARU --- */
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
        
        /* Banner untuk Souvenir */
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

        /* Style Tambahan untuk Tombol Radio Pilihan Kemasan/Kertas */
        .btn-check-custom + .btn { border-radius: 50px; font-size: 0.9rem; color: #64748B; border-color: #CBD5E1; background-color: white; transition: all 0.2s ease;}
        .btn-check-custom:checked + .btn { background-color: #64748B; color: white; border-color: #64748B; }
    </style>
</head>
<body id="beranda">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-kfc fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('template/img/smolie.jpg') }}" width="45" height="45" class="me-2 rounded-circle border border-2 border-white shadow-sm">
                <span class="ms-1" style="color: var(--primary-color);">Smolie Gift</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#menu-area">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://wa.me/62895395810940" target="_blank">Kontak</a></li>
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
        {{-- BANNER BARU UNTUK SOUVENIR --}}
        <div class="hero-banner">
            <h2 class="fw-bold mb-2 display-6">Cari Souvenir Unik & Cantik?</h2>
            <p class="mb-0 fs-5" style="opacity: 0.9;">Temukan beragam pilihan custom souvenir di Smolie Gift!</p>
        </div>

        @if(session('success'))
            <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background-color: #E8F5E9; color: #2E7D32;">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i> <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background-color: #FFEBEE; color: #C62828;">
                <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i> <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Layanan Pengambilan --}}
        <div class="mb-5">
            <h5 class="fw-bold mb-3 ps-2" style="color: var(--text-dark);">Metode Pengambilan Pesanan</h5>
            @php $layanan = session('jenis_pesanan', 'takeaway'); @endphp
            <div class="row g-3">
                <div class="col-6"><a href="{{ route('set.layanan', 'takeaway') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'takeaway' ? 'active-service' : '' }}"><i class="bi bi-shop fs-3 mb-2"></i><div class="fw-bold small">Ambil di Toko (Pickup)</div></div></a></div>
                <div class="col-6"><a href="{{ route('set.layanan', 'delivery') }}" class="text-decoration-none"><div class="service-option {{ $layanan == 'delivery' ? 'active-service' : '' }}"><i class="bi bi-box-seam-fill fs-3 mb-2"></i><div class="fw-bold small">Kirim via Ekspedisi</div></div></a></div>
            </div>
        </div>

        {{-- Menu Area --}}
        <div id="menu-area" class="pt-2">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0" style="color: var(--text-dark);">Katalog Produk</h4>
            </div>

            {{-- Filter Kategori --}}
            <div class="category-scroll">
                <button class="btn btn-cat active" data-filter="all">Semua Kategori</button>
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
                            
                            {{-- Pengecekan Stok dan Hak Akses Admin --}}
                            @if($p->stock <= 0)
                                {{-- JIKA STOK HABIS --}}
                                <button type="button" class="btn w-100 btn-sm rounded-pill fw-bold text-white" disabled style="background-color: #dc3545; opacity: 0.6; border:none; padding: 8px 0;">
                                    <i class="bi bi-x-circle me-1"></i> Stok Habis
                                </button>

                            @elseif(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                                {{-- JIKA YANG LOGIN ADALAH ADMIN/KASIR --}}
                                <button type="button" class="btn w-100 btn-sm rounded-pill fw-bold text-white" disabled style="background-color: #94a3b8; border:none; padding: 8px 0;" title="Admin hanya bisa melihat katalog">
                                    <i class="bi bi-eye me-1"></i> Preview Admin
                                </button>

                            @else
                                {{-- JIKA PEMBELI BIASA (Membuka Modal Order) --}}
                                <button type="button" class="btn-add btn w-100 btn-sm rounded-pill fw-bold text-white bg-danger" data-bs-toggle="modal" data-bs-target="#modalOrder-{{ $p->id }}" style="border:none; padding: 8px 0;">
                                    <i class="bi bi-plus-circle me-1"></i> Pesan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL PESANAN SOUVENIR --}}
                @if(!$isNonAktif)
                <div class="modal fade" id="modalOrder-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h4 class="modal-title fw-bold" style="color: var(--text-dark);">{{ $p->nama_produk }}</h4>
                                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" style="box-shadow: none;"></button>
                            </div>
                            
                            {{-- PENTING: METHOD POST & ENCTYPE UNTUK UPLOAD FILE DESAIN --}}
                            <form action="{{ route('add.to.cart', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body px-4 pb-4 pt-3">
                                    <input type="hidden" class="harga-dasar" value="{{ $p->harga }}">
                                    
                                    {{-- 1. Pilihan Warna / Tema --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark">Pilihan Warna / Motif</label>
                                        {{-- Tambahan ID dan onChange event --}}
                                        <select class="form-select border-secondary rounded-3" name="warna" id="pilihan-varian-{{ $p->id }}" onchange="cekPilihanCustom({{ $p->id }})">
                                            <option value="">Pilih varian...</option>
                                            <option value="Pastel">Warna Pastel (Soft)</option>
                                            <option value="Monokrom">Monokrom (Hitam Putih)</option>
                                            <option value="Emas">Aksen Emas (Gold)</option>
                                            <option value="Campur">Campur / Random</option>
                                            <option value="custom">Custom Desain Sendiri</option> {{-- Opsi Pemicu --}}
                                        </select>
                                    </div>

                                    {{-- 2. Ketebalan Kertas --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark">Ketebalan Kertas</label>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            {{-- Radio Buttons diubah gayanya pakai Bootstrap Button Group style --}}
                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas1-{{ $p->id }}" value="standar" data-price="0" checked onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-3 py-1 fw-semibold" for="kertas1-{{ $p->id }}">Standar (70gsm)</label>

                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas2-{{ $p->id }}" value="tebal" data-price="1000" onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-3 py-1 fw-semibold" for="kertas2-{{ $p->id }}">Tebal 80gsm (+Rp 1.000)</label>

                                            <input type="radio" class="btn-check btn-check-custom kemasan-radio" name="ketebalan" id="kertas3-{{ $p->id }}" value="ekstra" data-price="2500" onchange="hitungTotal({{ $p->id }})">
                                            <label class="btn px-3 py-1 fw-semibold" for="kertas3-{{ $p->id }}">Ekstra 100gsm (+Rp 2.500)</label>
                                        </div>
                                    </div>

                                    {{-- 3. Ekstra Tambahan --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark">Ekstra Tambahan</label>
                                        
                                        <div class="form-check p-3 border rounded-3 mb-2 d-flex justify-content-between align-items-center bg-light">
                                            <div>
                                                <input class="form-check-input ms-0 me-2 addon-check" type="checkbox" name="ekstra[]" value="Sablon" id="ext1-{{ $p->id }}" data-price="500" onchange="hitungTotal({{ $p->id }})">
                                                <label class="form-check-label fw-semibold" for="ext1-{{ $p->id }}">Sablon Nama / Inisial</label>
                                            </div>
                                            <span class="text-danger fw-bold">+Rp 500</span>
                                        </div>
                                        
                                        <div class="form-check p-3 border rounded-3 d-flex justify-content-between align-items-center bg-light">
                                            <div>
                                                <input class="form-check-input ms-0 me-2 addon-check" type="checkbox" name="ekstra[]" value="Kartu Ucapan" id="ext2-{{ $p->id }}" data-price="300" onchange="hitungTotal({{ $p->id }})">
                                                <label class="form-check-label fw-semibold" for="ext2-{{ $p->id }}">Thanks Card (Kartu Ucapan)</label>
                                            </div>
                                            <span class="text-danger fw-bold">+Rp 300</span>
                                        </div>
                                    </div>

                                    {{-- 4. Custom Desain (Disembunyikan dari awal pakai style display:none) --}}
                                    <div id="area-custom-{{ $p->id }}" style="display: none;">
                                        <div class="mb-4 p-3 rounded-4" style="background-color: #FDE8E5; border: 1px dashed #DD3827;">
                                            <label class="form-label fw-bold" style="color: #DD3827;">
                                                <i class="bi bi-palette-fill me-2"></i>Custom Desain & Catatan
                                            </label>
                                            <p class="small text-muted mb-2">Punya desain sendiri untuk sablon/kartu? Unggah di sini atau tulis instruksi untuk tim kami.</p>
                                            
                                            <input class="form-control mb-2 border-white shadow-sm" type="file" name="file_desain" accept="image/png, image/jpeg, application/pdf">
                                            <textarea class="form-control border-white shadow-sm" name="catatan_desain" rows="3" placeholder="Contoh: Tolong desain sablon font romantis. Tulisannya 'Nico & Lili'"></textarea>
                                        </div>
                                    </div>

                                    {{-- BAGIAN BAWAH MODAL (Hitung & Simpan) --}}
                                    <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                        <div class="qty-control shadow-sm">
                                            <button type="button" class="btn-qty" onclick="kurangModal({{ $p->id }})"><i class="bi bi-dash"></i></button>
                                            <input type="number" name="qty" id="qtyModal-{{ $p->id }}" class="input-qty fs-5" value="1" min="1" readonly>
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
    
    {{-- ================= FOOTER BARU SMOLIE GIFT ================= --}}
    <footer style="background-color: var(--text-dark); color: #b0b0b0; font-family: 'Nunito', sans-serif; padding-bottom: 120px;">
        <div style="height: 6px; background-color: var(--primary-color); width: 100%;"></div>

        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-family: 'Poppins', sans-serif;">
                        <img src="{{ asset('template/img/smolie.jpg') }}" width="45" height="45" class="me-2 rounded-circle border border-2 border-white shadow-sm">
                        SMOLIE GIFT
                    </h5>
                    <p class="small mb-4">Pusat pemesanan custom souvenir eksklusif, terpercaya, dan harga bersahabat untuk menyempurnakan hari bahagia Anda.</p>
                    <ul class="list-unstyled small">
                        <li class="mb-2 d-flex align-items-start"><i class="bi bi-geo-alt-fill me-2 mt-1" style="color: var(--primary-color);"></i> <span>Mojokerto, Jawa Timur</span></li>
                        <li class="mb-2 d-flex align-items-center"><i class="bi bi-clock-fill me-2" style="color: var(--primary-color);"></i> <span>Buka: Senin - Sabtu (08.00 - 16.00 WIB)</span></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-12 ms-auto">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small" style="font-family: 'Poppins', sans-serif;">Konsultasi Desain & Pesanan?</h6>
                    <a href="https://wa.me/62895395810940" target="_blank" class="btn w-100 fw-bold rounded-pill py-3 shadow-sm text-white" style="background-color: var(--primary-color); border: none; font-family: 'Poppins', sans-serif;">
                        <i class="bi bi-whatsapp me-2 fs-5"></i> CHAT WHATSAPP KAMI
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
                    <span class="small text-white" style="opacity: 0.8;">Total Keranjang</span>
                    <span class="fw-bold fs-6 text-white" style="font-family: 'Poppins', sans-serif;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="fw-bold small text-white" style="font-family: 'Poppins', sans-serif;">Proses Order <i class="bi bi-chevron-right ms-1"></i></div>
        </a>
    @endif

    {{-- ================= FLOATING CHAT PELANGGAN ================= --}}
    <button id="btn-chat-toggle" class="btn shadow-lg d-flex justify-content-center align-items-center" 
            style="position: fixed; bottom: 30px; right: 30px; z-index: 1060; background-color: #DD3827; color: white; border-radius: 50%; width: 60px; height: 60px; transition: 0.3s;">
        <i class="bi bi-chat-dots-fill fs-3"></i>
    </button>

    <div id="mini-chat-box" class="card shadow-lg d-none" 
         style="position: fixed; bottom: 100px; right: 30px; width: 350px; z-index: 1060; border-radius: 20px; border: none; overflow: hidden; font-family: 'Nunito', sans-serif;">
        
        <div class="card-header text-white d-flex justify-content-between align-items-center p-3 border-0" style="background-color: #DD3827;">
            <div class="d-flex align-items-center">
                <img src="{{ asset('template/img/smolie.jpg') }}" width="45" height="45" class="me-2 rounded-circle border border-2 border-white shadow-sm">
                <div>
                    <h6 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif; font-size: 0.95rem;">Admin Smolie</h6>
                    <small style="font-size: 0.75rem;"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i>Siap membalas pesan</small>
                </div>
            </div>
            <button id="btn-close-chat" class="btn btn-sm text-white border-0 shadow-none"><i class="bi bi-x-lg fs-5"></i></button>
        </div>
        
        <div id="chat-history-container" class="card-body p-3 overflow-auto custom-scrollbar" style="height: 320px; background-color: #F9F9FB; display: flex; flex-direction: column; gap: 12px;">
            <div class="text-center my-1">
                <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Hari ini</span>
            </div>

            <div class="d-flex w-100">
                <div class="p-2 px-3 rounded-4 text-dark bg-white border shadow-sm" style="max-width: 85%; font-size: 0.85rem; border-top-left-radius: 4px !important;">
                    Halo Kak! Ada yang bisa kami bantu untuk *custom* desain souvenirnya? 😊
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-white p-2 border-top">
            <div class="input-group align-items-center">
                <input type="text" id="chat-input-pesan" class="form-control border-0 shadow-none bg-light rounded-pill px-3 py-2" placeholder="Tulis pesan..." style="font-size: 0.85rem;">
                <button id="btn-kirim-chat" class="btn text-white rounded-circle ms-2 d-flex justify-content-center align-items-center shadow-sm" style="background-color: #DD3827; width: 40px; height: 40px;">
                    <i class="bi bi-send-fill" style="font-size: 0.9rem; margin-left: -2px;"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ================= SCRIPT JAVASCRIPT DI PALING BAWAH ================= --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- 1. FUNGSI GLOBAL (Bisa dipanggil dari mana saja) ---
        function cekPilihanCustom(id) {
            var valPilihan = $('#pilihan-varian-' + id).val();
            if(valPilihan === 'custom') {
                $('#area-custom-' + id).slideDown('fast');
            } else {
                $('#area-custom-' + id).slideUp('fast');
            }
        }

        function hitungTotal(id) {
            var modal = $('#modalOrder-' + id);
            var hargaDasar = parseInt(modal.find('.harga-dasar').val()) || 0;
            var qty = parseInt($('#qtyModal-' + id).val()) || 1;
            var kemasanPrice = parseInt(modal.find('.kemasan-radio:checked').data('price')) || 0;
            var totalAddon = 0;
            
            modal.find('.addon-check:checked').each(function() {
                totalAddon += parseInt($(this).data('price'));
            });

            var hargaPerItem = hargaDasar + kemasanPrice + totalAddon;
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

        // --- 2. FUNGSI KLIK (Wajib di dalam document.ready) ---
        $(document).ready(function() {
            // A. Kategori Filter
            $('.btn-cat').click(function() {
                $('.btn-cat').removeClass('active');
                $(this).addClass('active');
                var categoryId = $(this).data('filter');
                if(categoryId == 'all') { $('.product-item').fadeIn('fast'); } 
                else { $('.product-item').hide(); $('.product-item[data-category="' + categoryId + '"]').fadeIn('fast'); }
            });

            // B. Efek Navigasi
            $('.navbar-nav .nav-link').click(function() {
                $('.navbar-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            });

            // C. Toggle Chat Box
            $('#btn-chat-toggle').click(function() {
                var icon = $(this).find('i');
                if(icon.hasClass('bi-chat-dots-fill')) {
                    icon.removeClass('bi-chat-dots-fill').addClass('bi-x-lg');
                } else {
                    icon.removeClass('bi-x-lg').addClass('bi-chat-dots-fill');
                }
                $('#mini-chat-box').fadeToggle('fast').toggleClass('d-none');
                
                // Saat dibuka, langsung scroll ke bawah
                if(!$('#mini-chat-box').hasClass('d-none')){
                    var container = $('#chat-history-container');
                    container.scrollTop(container[0].scrollHeight);
                }
            });

            $('#btn-close-chat').click(function() {
                $('#mini-chat-box').fadeOut('fast').addClass('d-none');
                $('#btn-chat-toggle i').removeClass('bi-x-lg').addClass('bi-chat-dots-fill');
            });

            // D. Fitur Kirim Pesan AJAX
            $('#btn-kirim-chat').click(function() { kirimPesan(); });
            $('#chat-input-pesan').keypress(function(e) { if(e.which == 13) kirimPesan(); });

            function kirimPesan() {
                var pesanTeks = $('#chat-input-pesan').val();
                var btnKirim = $('#btn-kirim-chat');
                if(pesanTeks.trim() === '') return;

                btnKirim.html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: "{{ route('chat.kirim') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}", pesan: pesanTeks },
                    success: function(response) {
                        if(response.status === 'success') {
                            $('#chat-input-pesan').val('');
                            // Panggil refresh langsung agar tampilan sinkron
                            refreshChatPembeli();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        if(xhr.status === 401) {
                            alert("Silakan login terlebih dahulu.");
                            window.location.href = "{{ url('/login') }}";
                        }
                    },
                    complete: function() {
                        btnKirim.html('<i class="bi bi-send-fill"></i>');
                    }
                });
            }

            // E. Fitur Real-time Polling (Hanya untuk User yang Login)
            @auth
            setInterval(function() {
                if (!$('#mini-chat-box').hasClass('d-none')) {
                    refreshChatPembeli();
                }
            }, 5000);

            function refreshChatPembeli() {
                $.get("/admin/chat/messages/{{ Auth::id() }}", function(messages) {
                    let chatContainer = $('#chat-history-container');
                    let htmlContent = `<div class="text-center my-1"><span class="badge bg-light text-muted border small">Chat Smolie Gift</span></div>`;
                    
                    messages.forEach(msg => {
                        if (msg.pengirim === 'admin') {
                            htmlContent += `
                                <div class="d-flex w-100 mb-2">
                                    <div class="p-2 px-3 rounded-4 text-dark bg-white border shadow-sm" style="max-width: 85%; font-size: 0.85rem; border-top-left-radius: 4px !important;">
                                        ${msg.pesan}
                                    </div>
                                </div>`;
                        } else {
                            htmlContent += `
                                <div class="d-flex justify-content-end mb-2">
                                    <div class="p-2 px-3 rounded-4 text-white shadow-sm" style="background-color: #DD3827; max-width: 85%; font-size: 0.85rem; border-top-right-radius: 4px !important;">
                                        ${msg.pesan}
                                    </div>
                                </div>`;
                        }
                    });

                    // Gunakan perbandingan sederhana untuk mencegah refresh berlebihan
                    if (chatContainer.data('last-content') !== htmlContent) {
                        chatContainer.html(htmlContent);
                        chatContainer.data('last-content', htmlContent);
                        chatContainer.scrollTop(chatContainer[0].scrollHeight);
                    }
                });
            }
            @endauth
        });
    </script>
</body>
</html>