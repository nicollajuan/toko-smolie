<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Smolie Gift</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9F9FB;
            min-height: 100vh;
            padding: 40px 15px;
        }

        .page-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .back-link:hover { color: #DD3827; }

        .profile-header {
            background: linear-gradient(135deg, #DD3827, #FF7A68);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2);
        }

        .avatar-wrapper {
            width: 120px;
            height: 120px;
            margin: 0 auto 12px;
            position: relative;
        }

        .avatar-wrapper img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 38px;
            height: 38px;
            background-color: #D35400;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
            transition: 0.3s;
        }

        .avatar-edit-btn:hover { background-color: #A04000; }

        .badge-level {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 16px;
            border-radius: 50px;
            background: rgba(255,255,255,0.2);
            display: inline-block;
            margin-top: 6px;
        }

        .card-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            padding: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: 700;
            color: #2D3142;
            font-size: 1.05rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i { color: #DD3827; }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4F5665;
            margin-bottom: 5px;
        }

        .form-label span { color: red; }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #CBD5E1;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #DD3827;
            box-shadow: 0 0 0 0.2rem rgba(221, 56, 39, 0.15);
        }

        .input-group .form-control { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group .input-group-text { border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-left: none; background: white; }

        .password-toggle { cursor: pointer; color: #94A3B8; }

        .btn-submit {
            background-color: #DD3827;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 12px 30px;
            border: none;
            transition: 0.3s;
        }

        .btn-submit:hover { background-color: #C02E1F; color: white; }

        .danger-zone {
            border: 2px dashed #FFCDD2;
            border-radius: 16px;
            padding: 25px;
            background: #FFF8F8;
        }

        .danger-zone .section-title { color: #C62828; }
        .danger-zone .section-title i { color: #C62828; }

        .btn-danger-outline {
            background: white;
            color: #C62828;
            border: 2px solid #FFCDD2;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px 25px;
            transition: 0.3s;
        }

        .btn-danger-outline:hover {
            background: #C62828;
            color: white;
            border-color: #C62828;
        }

        .modal-content { border-radius: 20px; border: none; }
    </style>
</head>
<body>

    <div class="page-wrapper">

        <a href="{{ route('pembeli.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>

        {{-- HEADER PROFIL --}}
        <div class="profile-header">
            <div class="avatar-wrapper">
                @if($user->foto && file_exists(public_path('img/user/'.$user->foto)))
                    <img id="avatarPreview" src="{{ asset('img/user/' . $user->foto) }}" alt="Foto Profil">
                @else
                    <img id="avatarPreview" src="{{ asset('template/img/star.jpeg') }}" alt="Foto Default">
                @endif

                <label for="fotoInput" class="avatar-edit-btn">
                    <i class="bi bi-camera-fill text-white" style="font-size: 0.9rem;"></i>
                </label>
            </div>
            <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
            <p class="mb-0 small" style="opacity: 0.9;">{{ '@' . ($user->username ?? '-') }}</p>

            @php
                $lvl = $user->level_member ?? 'Bronze';
            @endphp
            <span class="badge-level">{{ $lvl }} Member</span>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert border-0 shadow-sm rounded-3 mb-3 fw-bold small" style="background-color: #E8F5E9; color: #2E7D32;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert border-0 shadow-sm rounded-3 mb-3" style="background-color: #FFEBEE; color: #C62828;">
                <ul class="mb-0 small fw-semibold ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UTAMA: SAMA SEPERTI FIELD REGISTER --}}
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="file" name="foto" id="fotoInput" accept="image/png, image/jpeg, image/jpg, image/gif" class="d-none" onchange="previewFoto(event)">

            <div class="card-section">
                <div class="section-title"><i class="bi bi-person-fill"></i> Informasi Akun</div>

                <div class="row">
                    {{-- Nama --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Nama <span>*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Email <span>*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>

                    {{-- Username --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Username <span>*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        @error('username') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>

                    {{-- Jenis Kelamin & No HP --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin <span>*</span></label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">Pilih...</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No HP <span>*</span></label>
                        <input type="number" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $user->no_hp) }}" required>
                        @error('no_hp') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12 mb-0">
                        <label class="form-label">Alamat <span>*</span></label>
                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat', $user->alamat) }}" required>
                        @error('alamat') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="card-section">
                <div class="section-title"><i class="bi bi-lock-fill"></i> Ubah Password</div>
                <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password.</p>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPass" class="form-control border-end-0 @error('current_password') is-invalid @enderror">
                            <span class="input-group-text password-toggle" onclick="togglePass('currentPass', 'iconCurrent')">
                                <i class="bi bi-eye" id="iconCurrent"></i>
                            </span>
                        </div>
                        @error('current_password') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPass" class="form-control border-end-0 @error('password') is-invalid @enderror">
                            <span class="input-group-text password-toggle" onclick="togglePass('newPass', 'iconNew')">
                                <i class="bi bi-eye" id="iconNew"></i>
                            </span>
                        </div>
                        @error('password') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-0">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPass" class="form-control border-end-0">
                            <span class="input-group-text password-toggle" onclick="togglePass('confirmPass', 'iconConfirm')">
                                <i class="bi bi-eye" id="iconConfirm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save2-fill me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- DANGER ZONE: HAPUS AKUN --}}
        <div class="danger-zone mb-5">
            <div class="section-title"><i class="bi bi-exclamation-triangle-fill"></i> Hapus Akun</div>
            <p class="text-muted small mb-3">
                Setelah akun dihapus, seluruh data akun kamu akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
            </p>
            <button type="button" class="btn-danger-outline" data-bs-toggle="modal" data-bs-target="#modalHapusAkun">
                <i class="bi bi-trash3-fill me-2"></i>Hapus Akun Saya
            </button>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS AKUN --}}
    <div class="modal fade" id="modalHapusAkun" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-danger rounded-circle" style="width: 70px; height: 70px;">
                            <i class="bi bi-trash3-fill fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Hapus Akun?</h5>
                    <p class="text-muted mb-3 small">Masukkan password kamu untuk konfirmasi. Akun akan dihapus permanen.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control @error('password', 'deleteAccount') is-invalid @enderror" placeholder="Masukkan password" required>
                            @error('password', 'deleteAccount')
                                <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm" style="border-radius: 12px;">
                                Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                // Submit otomatis form saat foto dipilih (langsung upload)
                event.target.closest('form').submit();
            }
        }

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

        {{-- Buka modal otomatis kalau ada error khusus delete account --}}
        @if ($errors->deleteAccount->any())
            document.addEventListener('DOMContentLoaded', function () {
                new bootstrap.Modal(document.getElementById('modalHapusAkun')).show();
            });
        @endif
    </script>
</body>
</html>