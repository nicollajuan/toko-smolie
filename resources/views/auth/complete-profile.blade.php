<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - Smolie Gift</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
            max-width: 600px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 40px 50px;
        }

        .form-title {
            font-weight: 800;
            color: #2D3142;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-subtitle {
            text-align: center;
            color: #94A3B8;
            font-size: 0.9rem;
            margin-bottom: 25px;
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
    </style>
</head>
<body>

    <div class="auth-card">
        <h2 class="form-title">Lengkapi Profil Kamu</h2>
        <p class="form-subtitle">Beberapa data tambahan diperlukan sebelum kamu bisa lanjut, {{ $user->name ?? auth()->user()->name }} 👋</p>

        <form method="POST" action="{{ route('complete-profile.store') }}">
            @csrf

            <div class="row">
                {{-- Username --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Username <span>*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                    @error('username') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                </div>

                {{-- Jenis Kelamin & No HP --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin <span>*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih...</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No HP <span>*</span></label>
                    <input type="number" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" required>
                    @error('no_hp') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                </div>

                {{-- Alamat --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat <span>*</span></label>
                    <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat') }}" required>
                    @error('alamat') <small class="text-danger mt-1 d-block fw-semibold">{{ $message }}</small> @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit">Simpan & Lanjutkan</button>
        </form>
    </div>

</body>
</html>.