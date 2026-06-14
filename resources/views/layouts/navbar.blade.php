<div class="sidebar-wrapper bg-white border-end shadow-sm">
    
    {{-- 1. HEADER LOGO (TETAP DI ATAS) --}}
    <div class="p-3 border-bottom mb-2">
        <a href="/" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('template/img/smolie.jpg') }}" alt="Logo" width="55" height="55" class="me-3 rounded-circle border border-2 p-1 shadow-sm" style="border-color: #DD3827 !important;">
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold text-uppercase" style="font-family: 'Poppins', sans-serif; color: #DD3827; line-height: 1.1;">Smolie</span>
                <span class="fs-4 fw-bold text-dark text-uppercase" style="font-family: 'Poppins', sans-serif; line-height: 1;">Gift</span>
            </div>
        </a>
    </div>

    {{-- 2. AREA MENU (BISA DI-SCROLL) --}}
    <div class="menu-section custom-scrollbar flex-grow-1 overflow-auto px-3">
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            
            @auth
                {{-- MENU KHUSUS ADMIN --}}
                @if(Auth::user()->usertype == 'admin')
                    <li class="nav-header ps-3 mt-2 mb-2 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1.5px; color: #999;">Kelola Toko</li>
                        
                    <li>
                        <a href="{{ url('/kategori') }}" class="nav-link d-flex align-items-center {{ Request::is('kategori*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-grid-fill me-3 fs-4"></i> Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tampil-produk') }}" class="nav-link d-flex align-items-center {{ Request::is('tampil-produk*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-box-seam-fill me-3 fs-4"></i> Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/laporan') }}" class="nav-link d-flex align-items-center {{ Request::is('laporan*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-file-earmark-bar-graph-fill me-3 fs-4"></i> Laporan
                        </a>
                    </li>

                    <li class="nav-header ps-3 mt-4 mb-2 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1.5px; color: #999;">Interaksi Pelanggan</li>
                    <li>
                        <a href="{{ route('admin.reviews') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/ulasan*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-star-half me-3 fs-4"></i> Ulasan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.chat') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/chat*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-chat-dots-fill me-3 fs-4"></i> Chat
                        </a>
                    </li>

                    <li class="nav-header ps-3 mt-4 mb-2 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1.5px; color: #999;">Pengaturan</li>
                    <li>
                        <a href="{{ route('admin.staf.index') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/staf*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-people-fill me-3 fs-4"></i> Kelola Staf
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.diskon') }}" class="nav-link d-flex align-items-center {{ Request::is('admin/diskon*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-percent me-3 fs-4"></i> Diskon Member
                        </a>
                    </li>
                @endif

                {{-- MENU KHUSUS KASIR --}}
                @if(Auth::user()->usertype == 'kasir')
                    <li class="nav-header ps-3 mt-2 mb-2 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1.5px; color: #999;">Kasir Menu</li>
                    <li>
                        <a href="{{ route('transaksi.kasir.menu') }}" class="nav-link d-flex align-items-center {{ Request::is('kasir/menu') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-shop-window me-3 fs-4"></i> Katalog
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/transaksi') }}" class="nav-link d-flex align-items-center {{ Request::is('transaksi*') ? 'active-smolie' : 'text-secondary' }}">
                            <i class="bi bi-receipt me-3 fs-4"></i> Transaksi
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </div>

    {{-- 3. PROFIL USER (TETAP DI BAWAH) --}}
    <div class="p-3 border-top bg-white">
        <div class="dropdown dropup">
            <a href="javascript:void(0)" onclick="toggleProfilMenu()" class="d-flex align-items-center link-dark text-decoration-none p-3 rounded-4 shadow-sm border" id="tombolProfil" style="background-color: #fff; transition: 0.3s;">
                
                @php
                    $userFoto = Auth::user()->foto && file_exists(public_path('img/user/'.Auth::user()->foto)) 
                                ? asset('img/user/' . Auth::user()->foto) 
                                : asset('template/img/smolie.jpg');
                @endphp
                <img src="{{ $userFoto }}" alt="Foto" width="45" height="45" class="rounded-circle me-3 object-fit-cover" style="border: 2px solid #DD3827;">

                <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <strong class="text-truncate fs-6 mb-0">{{ Auth::user()->name }}</strong>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">{{ Auth::user()->usertype }}</small>
                </div>
                
                <i class="bi bi-chevron-up ms-2 text-muted fw-bold" id="ikonPanah" style="transition: transform 0.3s;"></i>
            </a>
            
            <ul class="dropdown-menu shadow-lg border-0 w-100 p-2" id="menuProfilDropdown" style="display: none; position: absolute; bottom: 100%; left: 0; margin-bottom: 15px; border-radius: 15px;">
                <li><a class="dropdown-item py-2 fw-bold rounded-3" href="{{ route('profile') }}"><i class="bi bi-person me-3 text-primary"></i> Profil</a></li>
                <li><a class="dropdown-item py-2 fw-bold rounded-3" href="{{ route('profile.edit') }}"><i class="bi bi-pencil-square me-3 text-success"></i> Edit</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button type="button" class="dropdown-item text-danger fw-bold py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalLogoutKonfirmasi">
                        <i class="bi bi-box-arrow-right me-3"></i> Logout
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- 4. MODAL KONFIRMASI LOGOUT --}}
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

<style>
    .sidebar-wrapper { height: 100vh; display: flex; flex-direction: column; width: 100%; position: sticky; top: 0; }
    .nav-link { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem; padding: 15px 20px; border-radius: 12px !important; transition: all 0.2s ease-in-out; color: #555 !important; }
    .nav-link:hover { background-color: #FDE8E5; color: #DD3827 !important; transform: translateY(-2px); }
    .active-smolie { background-color: #DD3827 !important; color: white !important; box-shadow: 0 4px 12px rgba(221, 56, 39, 0.3); }
    .active-smolie i { color: white !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ddd; }
    #tombolProfil:hover { border-color: #DD3827 !important; background-color: #fff9f8 !important; }
</style>

<script>
    function toggleProfilMenu() {
        var menu = document.getElementById("menuProfilDropdown");
        var ikon = document.getElementById("ikonPanah");
        var open = menu.style.display === "block";
        menu.style.display = open ? "none" : "block";
        ikon.style.transform = open ? "rotate(0deg)" : "rotate(180deg)";
    }

    document.addEventListener('click', function(event) {
        var tombol = document.getElementById("tombolProfil");
        var menu = document.getElementById("menuProfilDropdown");
        if (tombol && menu && !tombol.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = "none";
            document.getElementById("ikonPanah").style.transform = "rotate(0deg)";
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        var modalLogout = document.getElementById('modalLogoutKonfirmasi');
        if(modalLogout) {
            document.body.appendChild(modalLogout);
        }
    });
</script>