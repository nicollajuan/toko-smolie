<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    {{-- 1. META VIEWPORT (PENTING UNTUK RESPONSIVE HP) --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 2. CSRF TOKEN (PENTING UNTUK AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smolie Gift')</title>

    {{-- FONTS: Diganti ke Poppins dan Nunito untuk kesan playful & modern --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- BOOTSTRAP 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- 3. DATATABLES (VERSI BOOTSTRAP 5 AGAR LEBIH RAPI) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <style>
        /* --- TIMBUKTOON THEME VARIABLES --- */
        :root {
            --theme-primary: #DD3827; /* Merah-Oranye ceria seperti di gambar */
            --theme-secondary: #FDE8E5; /* Merah sangat muda untuk background hover/aksen */
            --theme-dark: #2D3142; /* Biru dongker gelap untuk teks agar tidak terlalu kaku */
            --theme-gray: #F9F9FB; /* Abu-abu sangat muda untuk background body */
            --theme-text: #4F5665;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--theme-gray);
            overflow-x: hidden;
            color: var(--theme-text);
        }

        /* Heading menggunakan Poppins yang membulat */
        h1, h2, h3, h4, h5, h6, .theme-font {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--theme-dark);
            /* Menghapus text-transform: uppercase agar lebih ramah */
        }

        /* --- SIDEBAR STYLE --- */
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -260px;
            transition: margin .25s ease-out;
            background-color: #ffffff;
            border-right: none; /* Dihilangkan, diganti shadow halus */
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0,0,0,0.03);
        }

        #page-content-wrapper {
            min-width: 100vw;
            padding-left: 0;
            transition: all 0.25s ease-out;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper { margin-left: 0; }
            #page-content-wrapper { padding-left: 260px; }
            body.toggled #sidebar-wrapper { margin-left: -260px; }
            body.toggled #page-content-wrapper { padding-left: 0; }
        }

        @media (max-width: 768px) {
            body.toggled #sidebar-wrapper { margin-left: 0; }
        }

        /* --- BUTTONS --- */
        .btn {
            border-radius: 50px; /* Pil membulat penuh seperti di gambar */
            font-weight: 600;
            padding: 10px 24px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .btn-primary, .btn-success {
            background-color: var(--theme-primary);
            border-color: var(--theme-primary);
            color: white;
        }

        .btn-primary:hover, .btn-success:hover {
            background-color: #C02E1F;
            border-color: #C02E1F;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(221, 56, 39, 0.25);
        }

        .btn-outline-secondary {
            border-color: #E2E8F0;
            color: var(--theme-text);
            background-color: white;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--theme-secondary);
            color: var(--theme-primary);
            border-color: var(--theme-primary);
        }

        /* --- CARD STYLE (Lebih membulat & Halus) --- */
        .card {
            border: none;
            border-radius: 20px; /* Sudut sangat membulat */
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            background: white;
            transition: transform 0.3s ease;
        }

        /* --- TABLE STYLE --- */
        .table {
            color: var(--theme-text);
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--theme-primary) !important;
            color: white !important;
            border: none !important;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(221, 56, 39, 0.2);
        }

        thead.table-light tr th, table thead tr th {
            background-color: var(--theme-secondary) !important;
            color: var(--theme-primary) !important;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            padding: 15px !important;
        }
        
        /* Membulatkan ujung header tabel */
        table thead tr th:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
        table thead tr th:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }
        
        table tbody tr td {
            padding: 15px !important;
            vertical-align: middle;
            border-bottom: 1px dashed #E2E8F0;
        }
        
        /* Navbar Atas */
        .navbar {
            box-shadow: 0 4px 20px rgba(0,0,0,0.02) !important;
        }
    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">
        
        {{-- 1. SIDEBAR --}}
        <div id="sidebar-wrapper">
            @include('layouts.navbar')
        </div>

        {{-- 2. KONTEN HALAMAN (KANAN) --}}
        <div id="page-content-wrapper" class="d-flex flex-column min-vh-100">
            
            {{-- A. Navbar Atas --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom-0 sticky-top px-4 py-3">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary border-0 shadow-sm me-3 rounded-circle" id="menu-toggle" style="width: 45px; height: 45px; padding: 0;">
                        <i class="bi bi-list fs-4 m-0"></i>
                    </button>
                    <span class="fw-bold d-none d-md-block theme-font fs-5" style="color: var(--theme-primary);">Dashboard Admin</span>
                </div>
            </nav>

            {{-- B. Isi Konten Utama --}}
            <div class="container-fluid p-4 p-md-5 flex-grow-1">

                {{-- ======================================================== --}}
                {{-- ALERT GLOBAL                                             --}}
                {{-- ======================================================== --}}
                
                {{-- 1. Alert Sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #E8F5E9; border-radius: 16px; color: #2E7D32;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                            <span class="fw-bold">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 2. Alert Error --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #FFEBEE; border-radius: 16px; color: #C62828;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <span class="fw-bold">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- ======================================================== --}}

                @yield('content')
            </div>

            {{-- C. Footer --}}
            <footer class="bg-transparent py-4 mt-auto">
                <div class="container-fluid text-center text-muted small" style="font-family: 'Poppins', sans-serif;">
                    &copy; {{ date('Y') }} <strong style="color: var(--theme-primary);">Smolie Gift</strong>. All Rights Reserved.
                    @if(View::exists('layouts.footer'))
                        @include('layouts.footer')
                    @endif
                </div>
            </footer>

        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- DataTables JS (Versi BS5) --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Toggle Sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("body").toggleClass("toggled");
            });

            // Fix modal backdrop issue
            $('.modal').appendTo("body");

            // 4. AUTO HIDE ALERT (Hilang otomatis setelah 4 detik)
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>