<div class="d-flex flex-column flex-shrink-0 bg-white h-100 p-3">
    
    {{-- 1. HEADER LOGO --}}
    <a href="/" class="d-flex align-items-center mb-4 text-decoration-none">
        <img src="{{ asset('public/template/img/Smolie2.jpg') }}" alt="Logo" width="50" height="50" class="me-3 rounded-circle border border-2 p-1" style="border-color: #DD3827 !important;">
        <div class="d-flex flex-column">
            <span class="fs-4 fw-bold text-uppercase" style="font-family: 'Poppins', sans-serif; color: #DD3827; line-height: 1;">Smolie</span>
            <span class="fs-5 fw-bold text-dark text-uppercase" style="font-family: 'Poppins', sans-serif; line-height: 1;">Gift</span>
        </div>
    </a>

    {{-- KODE NAVBAR TOP YANG SALAH TEMPAT SUDAH DIHAPUS DARI SINI --}}

    {{-- 2. MENU --}}
    <div class="flex-grow-1 overflow-auto mb-3 custom-scrollbar">
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            
            <li class="nav-item">
                <a href="/home" class="nav-link d-flex align-items-center {{ Request::is('home') ? 'active-smolie' : 'text-secondary' }}">
                    <i class="bi bi-house-door-fill me-3 fs-5"></i> Home
                </a>
            </li>

            @auth
                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'kasir')
                    <li class="nav-header ps-3 mt-3 mb-2 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Admin</li>
                        
                    <li>
                        <a href="{{ url('/kategori') }}" class="nav-link d-flex align-items-center {{ Request::is('kategori*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-grid-fill me-3 fs-5"></i> Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tampil-produk') }}" class="nav-link d-flex align-items-center {{ Request::is('tampil-produk*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-box-seam-fill me-3 fs-5"></i> Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/laporan') }}" class="nav-link d-flex align-items-center {{ Request::is('laporan*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-file-earmark-bar-graph-fill me-3 fs-5"></i> Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/transaksi') }}" class="nav-link d-flex align-items-center {{ Request::is('transaksi*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-receipt me-3 fs-5"></i> Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reviews') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/ulasan*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-star-half me-3 fs-5"></i> Ulasan
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.chat') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/chat*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-chat-dots-fill me-3 fs-5"></i> Chat Pelanggan
                        </a>
                    </li>
                @endif
            @endauth

            <li class="mt-4">
                <a href="{{ route('pembeli.index') }}" target="_blank" class="btn w-100 shadow-sm d-flex align-items-center justify-content-center" 
                   style="background-color: #2D3142; color: white; border-radius: 50px;">
                    <i class="bi bi-cart-fill me-2"></i> BUKA MENU
                </a>
            </li>
        </ul>
    </div>

    <hr class="mt-auto">
    
    {{-- 3. PROFIL ADMIN --}}
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle p-2 rounded hover-bg-light" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            
            @if(Auth::user()->foto && file_exists(public_path('img/user/'.Auth::user()->foto)))
                <img src="{{ asset('img/user/' . Auth::user()->foto) }}" 
                     alt="Foto" 
                     width="40" height="40" 
                     class="rounded-circle me-2 border border-2 object-fit-cover" style="border-color: #DD3827 !important;">
            @else
                <img src="{{ asset('template/img/smolie.jpg') }}" 
                     alt="Foto Default" 
                     width="40" height="40" 
                     class="rounded-circle me-2 border border-2 object-fit-cover" style="border-color: #DD3827 !important;">
            @endif

            <div class="d-flex flex-column">
                <strong class="text-truncate" style="max-width: 150px; font-family:'Poppins', sans-serif; font-size: 0.9rem;">{{ Auth::user()->name }}</strong>
                
                <small class="text-muted text-uppercase" style="font-size: 0.75rem;">
                    {{ Auth::user()->usertype }}
                </small>
            </div>
        </a>
        
        <ul class="dropdown-menu text-small shadow border-0" aria-labelledby="dropdownUser2">
            <li><a class="dropdown-item" href="{{ route('profile') }}">Lihat Profil</a></li>
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger fw-bold">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Styling khusus sidebar agar mirip menu app */
    .nav-link {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 8px !important;
        transition: all 0.2s;
    }
    
    .nav-link:hover {
        background-color: #FDE8E5; /* Warna Pink Muda Smolie */
        color: #DD3827 !important;
    }

    /* Kelas aktif Smolie menggantikan KFC */
    .active-smolie {
        background-color: #DD3827 !important; /* Warna Merah Coral Smolie */
        color: white !important;
        box-shadow: 0 4px 10px rgba(221, 56, 39, 0.4);
    }
    
    .active-smolie i {
        color: white !important;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
    
    /* Scrollbar Tipis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #999; 
    }
</style>