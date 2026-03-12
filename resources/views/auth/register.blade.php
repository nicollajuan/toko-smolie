<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Warung Tahu Lontong</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Latar Belakang Merah Bata seperti di gambar */
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

        /* Lingkaran Merah di Kiri */
        .left-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .red-circle {
            width: 250px;
            height: 250px;
            background-color: #C0392B;
            border-radius: 50%;
        }

        /* Area Form Kanan */
        .right-side {
            padding: 40px 50px;
        }

        .form-title {
            font-weight: 800;
            color: #2D3142;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4F5665;
            margin-bottom: 5px;
        }
        
        .form-label span {
            color: red;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #CBD5E1;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C0392B;
            box-shadow: 0 0 0 0.2rem rgba(192, 57, 43, 0.15);
        }

        .btn-submit {
            background-color: #D35400; /* Warna Oranye Kemerahan seperti gambar */
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

        .btn-google {
            background-color: #FF4B4B;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px;
            width: 100%;
            border: none;
            margin-top: 10px;
        }

        .btn-facebook {
            background-color: #5C7CFA;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px;
            width: 100%;
            border: none;
            margin-top: 10px;
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
            
            {{-- Bagian Kiri: Lingkaran Merah --}}
            <div class="col-md-5 d-none d-md-flex left-side">
                <div class="red-circle"></div>
            </div>

            {{-- Bagian Kanan: Form Register --}}
            <div class="col-md-7 right-side">
                <h2 class="form-title">Buat Akun Baru!</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row">
                        {{-- Nama --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">Nama <span>*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">Email <span>*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        {{-- Username --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">Username <span>*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                        </div>

                        {{-- Jenis Kelamin & No HP (Sejajar) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP <span>*</span></label>
                            <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat <span>*</span></label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
                        </div>

                        {{-- Password & Konfirmasi (Sejajar) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span>*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="pass" class="form-control border-end-0" required>
                                <span class="input-group-text bg-white password-toggle" onclick="togglePass('pass', 'icon1')">
                                    <i class="bi bi-eye" id="icon1"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password <span>*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="pass2" class="form-control border-end-0" required>
                                <span class="input-group-text bg-white password-toggle" onclick="togglePass('pass2', 'icon2')">
                                    <i class="bi bi-eye" id="icon2"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Registrasi Akun</button>

                    <button type="button" class="btn-google">
                        <i class="bi bi-google me-2"></i> Register dengan Google
                    </button>

                    <button type="button" class="btn-facebook mb-4">
                        <i class="bi bi-facebook me-2"></i> Register dengan Facebook
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #3B82F6; font-size: 0.9rem;">Sudah memiliki akun? Login!</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

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