<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Tahu Lontong</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font Khas: Oswald & Open Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --kfc-red: #E4002B;
            --kfc-black: #202124;
            --kfc-gray: #f8f9fa;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: white;
            height: 100vh;
            overflow-x: hidden;
        }

        /* --- BAGIAN KIRI (GAMBAR) --- */
        .bg-image {
            /* Pastikan gambar ini ada, atau ganti dengan gambar lain */
            background-image: url('{{ asset("template/img/tahu.png") }}'); 
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 100vh;
        }
        
        .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(228, 0, 43, 0.4) 0%, rgba(32, 33, 36, 0.9) 100%);
        }

        .brand-text {
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
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
            max-width: 420px; /* Sedikit lebih kecil dari register agar proporsional */
        }

        .form-title {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem;
            color: var(--kfc-black);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-left: 5px solid var(--kfc-red);
            padding-left: 15px;
        }

        /* Styling Input */
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 12px 15px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--kfc-red);
            box-shadow: 0 0 0 0.2rem rgba(228, 0, 43, 0.15);
        }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #555;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        /* Tombol Utama */
        .btn-kfc {
            background-color: var(--kfc-red);
            color: white;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            padding: 12px;
            width: 100%;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-kfc:hover {
            background-color: #c00024;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(228, 0, 43, 0.3);
            color: white;
        }

        /* Divider & Links */
        .divider-text {
            display: flex;
            align-items: center;
            text-align: center;
            color: #aaa;
            font-size: 0.85rem;
            margin: 25px 0;
        }
        .divider-text::before, .divider-text::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }
        .divider-text:not(:empty)::before { margin-right: .5em; }
        .divider-text:not(:empty)::after { margin-left: .5em; }

        .link-red {
            color: var(--kfc-red);
            text-decoration: none;
            font-weight: 700;
        }
        .link-red:hover {
            text-decoration: underline;
        }
        
        .btn-social {
            border: 2px solid #eee;
            background: white;
            color: var(--kfc-black);
            font-weight: 600;
            border-radius: 50px;
            padding: 10px;
            transition: 0.2s;
        }
        .btn-social:hover {
            background: #f8f9fa;
            border-color: #ddd;
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
                    <div class="position-relative text-white mb-5" style="z-index: 2;">
                        <h1 class="brand-text display-3 fw-bold mb-2">SELAMAT DATANG</h1>
                        <p class="fs-4">Login untuk melanjutkan pesanan favorit Anda.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM LOGIN --}}
            <div class="col-lg-5 login-section">
                <div class="form-container">
                    
                    {{-- Logo Mobile --}}
                    <div class="text-center mb-4 d-lg-none">
                        <h2 class="brand-text text-danger fw-bold">TAHU LONTONG</h2>
                    </div>

                    <div class="mb-4">
                        <h2 class="form-title">LOGIN AKUN</h2>
                        <p class="text-muted">Silakan masukkan email dan password Anda.</p>
                    </div>

                    {{-- ALERT ERROR (Jika login gagal) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <div>Email atau Password salah.</div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- 1. EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        </div>

                        {{-- 2. PASSWORD --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-muted text-decoration-none">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="Masukkan password" required>
                                <span class="input-group-text bg-white border-start-0 text-muted" onclick="togglePass()" style="cursor: pointer;">
                                    <i class="bi bi-eye" id="iconPass"></i>
                                </span>
                            </div>
                        </div>

                        {{-- 3. REMEMBER ME --}}
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label small text-muted" for="remember_me">Ingat Saya</label>
                        </div>

                        <button type="submit" class="btn btn-kfc mb-3">MASUK SEKARANG</button>

                        <div class="text-center mb-3">
                            <span class="text-muted small">Belum punya akun? <a href="{{ route('register') }}" class="link-red">Daftar disini</a></span>
                        </div>
                        
                        {{-- Opsi Login Sosmed (Opsional) --}}
                        <div class="divider-text">ATAU MASUK DENGAN</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-social w-100">
                                    <i class="bi bi-google text-danger me-2"></i> Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-social w-100">
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