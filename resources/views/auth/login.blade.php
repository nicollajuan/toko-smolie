<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smolie Gift</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font Khas Tema Baru: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #C0392B; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background-color: white;
            border-radius: 20px;
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .left-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .img-circle {
            width: 260px;
            height: 260px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2);
            border: 6px solid #FDE8E5;
        }

        .right-side {
            padding: 50px 60px;
        }

        .form-title {
            font-weight: 800;
            color: #2D3142;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4F5665;
            margin-bottom: 8px;
        }

        .form-label span {
            color: red;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #CBD5E1;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #C0392B;
            box-shadow: 0 0 0 0.2rem rgba(192, 57, 43, 0.15);
        }

        .input-group .form-control { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group .input-group-text { border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-left: none; }

        .btn-submit {
            background-color: #D35400; 
            color: white;
            font-weight: 600;
            border-radius: 50px; 
            padding: 12px;
            width: 100%;
            border: none;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #A04000;
            color: white;
        }

        .divider-text {
            display: flex;
            align-items: center;
            text-align: center;
            color: #94A3B8;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 25px 0;
        }
        
        .divider-text::before, .divider-text::after {
            content: '';
            flex: 1;
            border-bottom: 2px dashed #E2E8F0;
        }
        
        .divider-text:not(:empty)::before { margin-right: 1em; }
        .divider-text:not(:empty)::after { margin-left: 1em; }

        .btn-social {
            border: 2px solid #E2E8F0;
            background: white;
            color: #2D3142;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px;
            transition: 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-social svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .btn-social:hover {
            background: #F9F9FB;
            border-color: #CBD5E1;
            transform: translateY(-2px);
            color: #2D3142;
        }

        .password-toggle {
            cursor: pointer;
            color: #94A3B8;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="row g-0">
            
            {{-- Bagian Kiri: Lingkaran Logo & Teks --}}
            <div class="col-md-5 d-none d-md-flex left-side flex-column text-center" style="background-color: #FAFAFA;">
                <img src="{{ asset('template/img/smolie.jpg') }}" alt="Profil Smolie Gift" class="img-circle mb-4">
                <h3 class="fw-bold mb-1" style="color: #2D3142; font-family: 'Poppins', sans-serif;">Smolie Gift</h3>
                <p class="text-muted small px-4">Souvenir hampers packaging untuk melengkapi kebutuhan acaramu 🎉🎂🍬</p>
            </div>

            {{-- Bagian Kanan: Form Login --}}
            <div class="col-md-7 right-side">
                <h2 class="form-title">Login Akun!</h2>

                {{-- Tombol Login Google --}}
                <a href="{{ route('auth.google') }}" class="btn-social mb-3">
                    <svg width="20" height="20" viewBox="0 0 48 48">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12
                            c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24
                            c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039
                            l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36
                            c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571
                            c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24
                            C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                    <span>Login dengan Google</span>
                </a>

                <div class="divider-text">atau login dengan email</div>

                {{-- ALERT ERROR --}}
                @if ($errors->any())
                    <div class="alert border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" style="background-color: #FFEBEE; color: #C62828;">
                        <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i>
                        <div class="fw-bold small">Email atau Password salah.</div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert border-0 shadow-sm rounded-3 mb-4 fw-bold small" style="background-color: #E8F5E9; color: #2E7D32;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    {{-- 1. EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label">Email <span>*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>

                    {{-- 2. PASSWORD --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Password <span>*</span></label>
                        </div>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control border-end-0" required>
                            <span class="input-group-text bg-white password-toggle" onclick="togglePass()">
                                <i class="bi bi-eye" id="iconPass"></i>
                            </span>
                        </div>
                    </div>

                    {{-- 3. REMEMBER ME & LUPA PASSWORD --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input shadow-sm" id="remember_me" name="remember" style="cursor: pointer;">
                            <label class="form-check-label small fw-semibold text-muted" for="remember_me" style="cursor: pointer;">Ingat Saya</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small fw-semibold text-decoration-none" style="color: #3B82F6;">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit mb-4">Masuk Sekarang</button>

                </form>

                {{-- Area Tautan Tambahan (Di Luar Form) --}}
                <div class="text-center mb-4 position-relative" style="z-index: 999; pointer-events: auto;">
                    <span class="text-muted fw-semibold" style="font-size: 0.95rem;">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none" style="cursor: pointer;">
                            Daftar disini
                        </a>
                    </span>
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