<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Tahu Lontong</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font Khas Tema Baru: Poppins & Nunito --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- VARIABEL TEMA BARU --- */
        :root {
            --theme-primary: #DD3827; /* Merah Coral Ceria */
            --theme-secondary: #FDE8E5; /* Pink Muda */
            --theme-dark: #2D3142; 
            --theme-gray: #F9F9FB; 
            --theme-text: #4F5665;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: white;
            height: 100vh;
            overflow-x: hidden;
            color: var(--theme-text);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            color: var(--theme-dark);
        }

        /* --- BAGIAN KIRI (GAMBAR) --- */
        .bg-image {
            background-image: url('{{ asset("template/img/tahu.png") }}'); 
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 100vh;
        }
        
        .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* Gradasi disesuaikan jadi lebih cerah dan hangat */
            background: linear-gradient(180deg, rgba(221, 56, 39, 0.3) 0%, rgba(45, 49, 66, 0.95) 100%);
        }

        .brand-text {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
        }

        /* --- BAGIAN KANAN (FORM) --- */
        .login-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px;
            background-color: white;
        }

        .form-container {
            width: 100%;
            max-width: 420px; 
        }

        .form-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            border-left: 6px solid var(--theme-primary);
            border-radius: 4px;
            padding-left: 15px;
            color: var(--theme-dark);
        }

        /* Styling Input Kekinian */
        .form-control {
            border: 2px solid #E2E8F0;
            border-radius: 16px; /* Sudut membulat */
            padding: 12px 18px;
            font-weight: 600;
            transition: all 0.3s;
            color: var(--theme-dark);
        }

        .form-control:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 0.25rem rgba(221, 56, 39, 0.15);
        }

        /* Perbaikan sudut untuk Input Group (Mata Password) */
        .input-group .form-control { border-top-right-radius: 0; border-bottom-right-radius: 0; border-right: none; }
        .input-group .input-group-text { 
            border: 2px solid #E2E8F0; 
            border-left: none; 
            border-top-right-radius: 16px; 
            border-bottom-right-radius: 16px; 
            background: white; 
            color: #94A3B8;
        }
        .input-group:focus-within .form-control { border-color: var(--theme-primary); }
        .input-group:focus-within .input-group-text { border-color: var(--theme-primary); color: var(--theme-primary); }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #64748B;
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }

        /* Tombol Utama */
        .btn-theme {
            background-color: var(--theme-primary);
            color: white;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 50px; /* Bentuk kapsul */
            padding: 12px;
            width: 100%;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(221, 56, 39, 0.2);
        }

        .btn-theme:hover {
            background-color: #C02E1F;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(221, 56, 39, 0.3);
            color: white;
        }

        /* Divider & Links */
        .divider-text {
            display: flex;
            align-items: center;
            text-align: center;
            color: #94A3B8;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 25px 0;
            font-family: 'Poppins', sans-serif;
        }
        .divider-text::before, .divider-text::after {
            content: '';
            flex: 1;
            border-bottom: 2px dashed #E2E8F0;
        }
        .divider-text:not(:empty)::before { margin-right: 1em; }
        .divider-text:not(:empty)::after { margin-left: 1em; }

        .link-theme {
            color: var(--theme-primary);
            text-decoration: none;
            font-weight: 700;
        }
        .link-theme:hover {
            text-decoration: underline;
        }
        
        .btn-social {
            border: 2px solid #E2E8F0;
            background: white;
            color: var(--theme-dark);
            font-weight: 600;
            border-radius: 50px;
            padding: 10px;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-social:hover {
            background: var(--theme-gray);
            border-color: #CBD5E1;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="container-fluid g-0">
        <div class="row g-0">
            
            {{-- KOLOM KIRI: GAMBAR --}}
            <div class="col-lg-7 d-none d-lg-block">
                <div class="bg-image d-flex align-items-end p-5">
                    <div class="bg-overlay"></div>
                    <div class="position-relative text-white mb-5 ms-4" style="z-index: 2;">
                        <span class="badge mb-3 px-3 py-2 rounded-pill fw-bold" style="background-color: var(--theme-primary); letter-spacing: 1px;">SISTEM KASIR</span>
                        <h1 class="brand-text display-3 fw-bold mb-2">Selamat Datang!</h1>
                        <p class="fs-5 opacity-75">Login untuk melanjutkan kelola warung favorit Anda.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM LOGIN --}}
            <div class="col-lg-5 login-section">
                <div class="form-container">
                    
                    {{-- Logo Mobile --}}
                    <div class="text-center mb-4 d-lg-none">
                        <img src="{{ asset('template/img/tahu.png') }}" width="60" height="60" class="rounded-circle shadow-sm mb-2">
                        <h3 class="brand-text fw-bold" style="color: var(--theme-primary);">Tahu Lontong</h3>
                    </div>

                    <div class="mb-4">
                        <h2 class="form-title">Login Akun</h2>
                        <p class="text-muted">Silakan masukkan email dan password Anda.</p>
                    </div>

                    {{-- ALERT ERROR (Jika login gagal) --}}
                    @if ($errors->any())
                        <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background-color: #FFEBEE; color: #C62828;">
                            <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i>
                            <div class="fw-bold">Email atau Password salah.</div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert border-0 shadow-sm rounded-4 mb-4 fw-bold" style="background-color: #E8F5E9; color: #2E7D32;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- 1. EMAIL --}}
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        </div>

                        {{-- 2. PASSWORD --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small link-theme text-decoration-none">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="input-group shadow-sm" style="border-radius: 16px;">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                                <span class="input-group-text" onclick="togglePass()" style="cursor: pointer;">
                                    <i class="bi bi-eye" id="iconPass"></i>
                                </span>
                            </div>
                        </div>

                        {{-- 3. REMEMBER ME --}}
                        <div class="mb-4 form-check ps-4 pt-1">
                            <input type="checkbox" class="form-check-input shadow-sm" id="remember_me" name="remember" style="transform: scale(1.2); cursor: pointer;">
                            <label class="form-check-label small fw-semibold text-muted ms-2" for="remember_me" style="cursor: pointer;">Ingat Saya</label>
                        </div>

                        <button type="submit" class="btn btn-theme mb-4">Masuk Sekarang</button>

                        <div class="text-center mb-3">
                            <span class="text-muted fw-semibold">Belum punya akun? <a href="{{ route('register') }}" class="link-theme">Daftar disini</a></span>
                        </div>
                        
                        {{-- Opsi Login Sosmed (Opsional) --}}
                        <div class="divider-text">ATAU MASUK DENGAN</div>
                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-social w-100 shadow-sm">
                                    <i class="bi bi-google text-danger me-2"></i> Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-social w-100 shadow-sm">
                                    <i class="bi bi-facebook text-primary me-2"></i> Facebook
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Toggle Password --}}
    <script>
        function togglePass() {
            let input = document.getElementById("password");
            let icon = document.getElementById("iconPass");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

</body>
</html>