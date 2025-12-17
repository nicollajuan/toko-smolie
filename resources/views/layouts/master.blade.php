<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    {{-- 1. META VIEWPORT (PENTING UNTUK RESPONSIVE HP) --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 2. CSRF TOKEN (PENTING UNTUK AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Warung Tahu Lontong')</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">

    {{-- BOOTSTRAP 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- 3. DATATABLES (VERSI BOOTSTRAP 5 AGAR LEBIH RAPI) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <style>
        /* --- KFC THEME VARIABLES --- */
        :root {
            --kfc-red: #E4002B;
            --kfc-dark: #202124;
            --kfc-gray: #f8f9fa;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--kfc-gray);
            overflow-x: hidden;
            color: var(--kfc-dark);
        }

        h1, h2, h3, h4, h5, h6, .kfc-font {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* --- SIDEBAR STYLE --- */
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -260px;
            transition: margin .25s ease-out;
            background-color: #ffffff;
            border-right: 1px solid #eee;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        #page-content-wrapper {
            min-width: 100vw;
            padding-left: 0; /* Default mobile first */
            transition: all 0.25s ease-out;
        }

        /* Tampilan Desktop (>768px) */
        @media (min-width: 768px) {
            #sidebar-wrapper { margin-left: 0; }
            #page-content-wrapper { padding-left: 260px; }
            
            /* Saat ditoggle di desktop, sidebar sembunyi */
            body.toggled #sidebar-wrapper { margin-left: -260px; }
            body.toggled #page-content-wrapper { padding-left: 0; }
        }

        /* Tampilan Mobile (<768px) */
        @media (max-width: 768px) {
            /* Saat ditoggle di mobile, sidebar muncul */
            body.toggled #sidebar-wrapper { margin-left: 0; }
        }

        /* --- BUTTONS --- */
        .btn {
            border-radius: 50px;
            font-weight: 600;
            padding: 8px 20px;
            text-transform: uppercase;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            transition: all 0.2s;
        }

        .btn-primary, .btn-success {
            background-color: var(--kfc-red);
            border-color: var(--kfc-red);
            color: white;
        }

        .btn-primary:hover, .btn-success:hover {
            background-color: #b00020;
            border-color: #b00020;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(228, 0, 43, 0.3);
        }

        .btn-outline-secondary {
            border-color: #ddd;
            color: var(--kfc-dark);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--kfc-dark);
            color: white;
        }

        /* --- CARD & TABLE STYLE --- */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            background: white;
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--kfc-red) !important;
            color: white !important;
            border: 1px solid var(--kfc-red) !important;
            border-radius: 50%;
        }

        thead.table-light tr th {
            background-color: var(--kfc-red) !important;
            color: white !important;
            border: none;
            font-family: 'Oswald', sans-serif;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        table tr:first-child th:first-child { border-top-left-radius: 10px; }
        table tr:first-child th:last-child { border-top-right-radius: 10px; }
    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">
        
        {{-- 1. SIDEBAR --}}
        <div id="sidebar-wrapper" class="shadow-sm">
            @include('layouts.navbar')
        </div>

        {{-- 2. KONTEN HALAMAN (KANAN) --}}
        <div id="page-content-wrapper" class="d-flex flex-column min-vh-100">
            
            {{-- A. Navbar Atas --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm px-4 py-3">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light border shadow-sm me-3" id="menu-toggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <span class="fw-bold d-none d-md-block kfc-font text-secondary">Dashboard Admin</span>
                </div>
            </nav>

            {{-- B. Isi Konten Utama --}}
            <div class="container-fluid p-4 flex-grow-1">

                {{-- ======================================================== --}}
                {{-- ALERT GLOBAL                                             --}}
                {{-- ======================================================== --}}
                
                {{-- 1. Alert Sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #d1e7dd; border-left: 5px solid #198754; color: #0f5132;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <span class="fw-bold">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 2. Alert Error --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 5px solid #dc3545;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <span class="fw-bold">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- ======================================================== --}}

                @yield('content')
            </div>

            {{-- C. Footer --}}
            <footer class="bg-white py-3 border-top mt-auto">
                <div class="container-fluid text-center text-muted small">
                    &copy; {{ date('Y') }} <strong>Warung Tahu Lontong</strong>. All Rights Reserved.
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