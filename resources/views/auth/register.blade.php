<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Warung Tahu Lontong</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font Khas KFC: Oswald (Judul) & Open Sans (Teks) --}}
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
            /* Ganti URL ini dengan foto Tahu Lontong Anda */
            background-image: url('{{ asset("template/img/tahu.png") }}'); 
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 100vh;
        }
        
        .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(228, 0, 43, 0.4) 0%, rgba(32, 33, 36, 0.8) 100%);
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
            max-width: 450px;
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

        /* Styling Input ala KFC */
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px; /* Sedikit membulat */
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
            border-radius: 50px; /* Pill shape */
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

        /* Tombol Sosmed */
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

        .divider-text {
            display: flex;
            align-items: center;
            text-align: center;
            color: #aaa;
            font-size: 0.85rem;
            margin: 20px 0;
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
    </style>
</head>
<body>

    <div class="container-fluid g-0">
        <div class="row g-0">
            
            {{-- KOLOM KIRI: GAMBAR (Hanya muncul di Layar Besar) --}}
            <div class="col-lg-7 d-none d-lg-block">
                <div class="bg-image d-flex align-items-end p-5">
                    <div class="bg-overlay"></div>
                    <div class="position-relative text-white mb-5" style="z-index: 2;">
                        <h1 class="brand-text display-3 fw-bold mb-2">LEZAT & GURIH</h1>
                        <p class="fs-4">Bergabunglah sekarang dan nikmati Tahu Lontong legendaris dengan harga spesial.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM REGISTER --}}
            <div class="col-lg-5 login-section">
                <div class="form-container">
                    
                    {{-- Logo Mobile (Opsional) --}}
                    <div class="text-center mb-4 d-lg-none">
                        <h2 class="brand-text text-danger fw-bold">TAHU LONTONG</h2>
                    </div>

                    <div class="mb-4">
                        <h2 class="form-title">BUAT AKUN BARU</h2>
                        <p class="text-muted">Lengkapi data diri Anda untuk mulai memesan.</p>
                    </div>

                    {{-- ALERT ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        {{-- 1. NAMA LENGKAP --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                        </div>

                        {{-- 2. EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                        </div>

                        {{-- 3. PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="Minimal 8 karakter" required>
                                <span class="input-group-text bg-white border-start-0 text-muted" onclick="togglePass('password', 'icon1')" style="cursor: pointer;">
                                    <i class="bi bi-eye" id="icon1"></i>
                                </span>
                            </div>
                        </div>

                        {{-- 4. CONFIRM PASSWORD --}}
                        <div class="mb-4">
                            <label class="form-label">Ulangi Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirm" class="form-control border-end-0" placeholder="Ketik ulang password" required>
                                <span class="input-group-text bg-white border-start-0 text-muted" onclick="togglePass('password_confirm', 'icon2')" style="cursor: pointer;">
                                    <i class="bi bi-eye" id="icon2"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kfc mb-3">DAFTAR SEKARANG</button>

                        <div class="text-center mb-3">
                            <span class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="link-red">Login disini</a></span>
                        </div>

                        <div class="divider-text">ATAU DAFTAR DENGAN</div>

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
        function togglePass(inputId, iconId) {
            let input = document.getElementById(inputId);
            let icon = document.getElementById(iconId);
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