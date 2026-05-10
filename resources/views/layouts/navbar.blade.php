<div class="d-flex flex-column flex-shrink-0 bg-white h-100 p-3">
    
    {{-- 1. HEADER LOGO --}}
    <a href="/" class="d-flex align-items-center mb-4 text-decoration-none">
        <img src="{{ asset('public/template/img/Smolie2.jpg') }}" alt="Logo" width="50" height="50" class="me-3 rounded-circle border border-2 p-1" style="border-color: #DD3827 !important;">
        <div class="d-flex flex-column">
            <span class="fs-4 fw-bold text-uppercase" style="font-family: 'Poppins', sans-serif; color: #DD3827; line-height: 1;">Smolie</span>
            <span class="fs-5 fw-bold text-dark text-uppercase" style="font-family: 'Poppins', sans-serif; line-height: 1;">Gift</span>
        </div>
    </a>

    {{-- 2. MENU --}}
    <div class="flex-grow-1 overflow-auto mb-3 custom-scrollbar">
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            
            @auth
                {{-- MENU KHUSUS ADMIN (FULL AKSES) --}}
                @if(Auth::user()->usertype == 'admin')
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
                @endif

                {{-- MENU KHUSUS KASIR SAJA --}}
                @if(Auth::user()->usertype == 'kasir')
                    <li class="nav-header ps-3 mt-3 mb-2 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Kasir</li>
                    <li>
                        <a href="{{ url('/transaksi') }}" class="nav-link d-flex align-items-center {{ Request::is('transaksi*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-receipt me-3 fs-5"></i> Transaksi
                        </a>
                    </li>
                @endif

                {{-- FITUR TAMBAHAN KHUSUS ADMIN --}}
                @if(Auth::user()->usertype == 'admin')
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
        </ul>
    </div>

    {{-- 3. PROFIL USER & DROPDOWN (VERSI KLIK MANUAL ANTI-GAGAL) --}}
    <div class="dropdown dropup mt-auto position-relative">
        <hr class="mb-2 text-secondary">
        
        {{-- Tombol Profil yang Diklik --}}
        <a href="javascript:void(0)" onclick="toggleProfilMenu()" class="d-flex align-items-center link-dark text-decoration-none p-2 rounded hover-bg-light" id="tombolProfil" style="border: 1px solid #e9ecef; background-color: #f8f9fa;">
            
            @if(Auth::user()->foto && file_exists(public_path('img/user/'.Auth::user()->foto)))
                <img src="{{ asset('img/user/' . Auth::user()->foto) }}" alt="Foto" width="40" height="40" class="rounded-circle me-2 object-fit-cover" style="border: 2px solid #DD3827;">
            @else
                <img src="{{ asset('template/img/smolie.jpg') }}" alt="Foto Default" width="40" height="40" class="rounded-circle me-2 object-fit-cover" style="border: 2px solid #DD3827;">
            @endif

            <div class="d-flex flex-column flex-grow-1">
                <strong class="text-truncate" style="max-width: 120px; font-family:'Poppins', sans-serif; font-size: 0.85rem;">{{ Auth::user()->name }}</strong>
                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ Auth::user()->usertype }}</small>
            </div>
            
            {{-- Ikon Panah Kecil --}}
            <i class="bi bi-chevron-up ms-auto text-muted" id="ikonPanah" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
        </a>
        
        {{-- Menu Dropdown yang disembunyikan --}}
        <ul class="dropdown-menu shadow border-0 w-100" id="menuProfilDropdown" style="display: none; position: absolute; bottom: 100%; left: 0; margin-bottom: 5px; border-radius: 10px;">
            <li>
                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile') }}" style="font-size: 0.9rem;">
                    <i class="bi bi-person me-3 fs-5 text-secondary"></i> Lihat Profil
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile.edit') }}" style="font-size: 0.9rem;">
                    <i class="bi bi-pencil-square me-3 fs-5 text-secondary"></i> Edit Profil
                </a>
            </li>
            <li><hr class="dropdown-divider m-0"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger fw-bold py-2 d-flex align-items-center" style="font-size: 0.9rem;">
                        <i class="bi bi-box-arrow-right me-3 fs-5"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div> {{-- Penutup tag <div class="d-flex flex-column..."> paling atas --}}

{{-- SCRIPT PENGGERAK DROPDOWN --}}
<script>
    function toggleProfilMenu() {
        var menu = document.getElementById("menuProfilDropdown");
        var ikon = document.getElementById("ikonPanah");
        
        if (menu.style.display === "none" || menu.style.display === "") {
            menu.style.display = "block";
            ikon.style.transform = "rotate(180deg)"; // Panah muter ke bawah
        } else {
            menu.style.display = "none";
            ikon.style.transform = "rotate(0deg)"; // Panah muter ke atas
        }
    }

    // Menutup menu jika klik di sembarang tempat (di luar area profil)
    document.addEventListener('click', function(event) {
        var tombol = document.getElementById("tombolProfil");
        var menu = document.getElementById("menuProfilDropdown");
        var ikon = document.getElementById("ikonPanah");
        
        if (!tombol.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = "none";
            ikon.style.transform = "rotate(0deg)";
        }
    });
</script>

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