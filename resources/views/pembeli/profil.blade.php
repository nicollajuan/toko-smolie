<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Smolie Gift</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9F9FB;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand { font-weight: 700; color: #202124 !important; font-size: 1.1rem; }
        .btn-back {
            background-color: #f1f2f6;
            color: #202124;
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-back:hover { background-color: #e2e6ea; color: #202124; }

        /* HERO PROFIL */
        .profile-hero {
            background: linear-gradient(135deg, #DD3827, #FF7A68);
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 15px 35px rgba(221, 56, 39, 0.2);
        }
        .avatar-circle {
            width: 90px; height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        /* KARTU */
        .card-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            padding: 24px;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: 700;
            font-size: 1rem;
            color: #2D3142;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i { color: #DD3827; }

        /* INFO ROW */
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 38px; height: 38px;
            background: #FFF0EE;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #DD3827;
            font-size: 1rem;
        }
        .info-label { font-size: 0.75rem; color: #94A3B8; font-weight: 600; margin-bottom: 2px; }
        .info-value { font-size: 0.9rem; font-weight: 600; color: #2D3142; }

        /* KARTU LOYALITAS */
        .loyalty-card {
            border-radius: 20px;
            padding: 24px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .loyalty-card::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .loyalty-card::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -30px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .loyalty-bronze { background: linear-gradient(135deg, #CD7F32, #A0522D); }
        .loyalty-silver  { background: linear-gradient(135deg, #8E9EAB, #5B6E7C); }
        .loyalty-gold    { background: linear-gradient(135deg, #F7C83A, #D4A017); }
        .loyalty-platinum { background: linear-gradient(135deg, #2C2C2C, #555); }
        .loyalty-gold .text-white { color: #3D2B00 !important; }
        .loyalty-gold .opacity-80 { color: rgba(61,43,0,0.75) !important; }

        .loyalty-chip {
            width: 42px; height: 32px;
            background: rgba(255,255,255,0.25);
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .loyalty-level-badge {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .loyalty-progress {
            height: 8px;
            border-radius: 50px;
            background: rgba(255,255,255,0.25);
            overflow: hidden;
            margin-top: 16px;
        }
        .loyalty-progress-bar {
            height: 100%;
            border-radius: 50px;
            background: rgba(255,255,255,0.7);
            transition: width 0.6s ease;
        }

        /* TABEL RIWAYAT */
        .table-riwayat thead th {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94A3B8;
            border-bottom: 2px solid #F1F5F9;
            padding: 10px 16px;
            background: white;
        }
        .table-riwayat tbody td {
            font-size: 0.85rem;
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #F8FAFC;
        }
        .badge-selesai { background: #D4EDDA; color: #155724; border-radius: 20px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: #FFF3CD; color: #856404; border-radius: 20px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-dikirim { background: #D1ECF1; color: #0C5460; border-radius: 20px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; }

        .btn-edit-profil {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-edit-profil:hover { background: rgba(255,255,255,0.35); color: white; }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar sticky-top mb-4">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembeli.index') }}" class="btn-back me-3">
                    <i class="bi bi-arrow-left"></i> Beranda
                </a>
                <span class="navbar-brand mb-0">Profil Saya</span>
            </div>
        </div>
    </nav>

    <div class="container pb-5" style="max-width: 720px;">

        {{-- FLASH --}}
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- HERO PROFIL --}}
        <div class="profile-hero d-flex align-items-center gap-4">
            @if($user->foto && file_exists(public_path('img/user/'.$user->foto)))
                <img src="{{ asset('img/user/' . $user->foto) }}" class="avatar-circle" alt="Foto Profil">
            @else
                <img src="{{ asset('template/img/star.jpeg') }}" class="avatar-circle" alt="Foto Default">
            @endif
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                <p class="mb-1 small" style="opacity:0.85;">{{ '@' . ($user->username ?? '-') }}</p>
                <p class="mb-2 small" style="opacity:0.75;"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                <a href="{{ route('profile.edit') }}" class="btn-edit-profil">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Profil
                </a>
            </div>
        </div>

        {{-- KARTU LOYALITAS - SHORTCUT --}}
        @php
            $currentTier = $user->level_member ?? 'Bronze';
            $tierClass = match($currentTier) {
                'Silver' => 'loyalty-silver',
                'Gold' => 'loyalty-gold',
                'Platinum' => 'loyalty-platinum',
                default => 'loyalty-bronze',
            };
            $tierIcon = match($currentTier) {
                'Silver' => 'bi-award-fill',
                'Gold' => 'bi-trophy',
                'Platinum' => 'bi-trophy-fill',
                default => 'bi-award',
            };
        @endphp
        <a href="{{ route('pembeli.kartu-member') }}" class="loyalty-card {{ $tierClass }} d-flex align-items-center justify-content-between text-decoration-none" style="cursor:pointer;">
            <div>
                <div class="loyalty-level-badge">
                    <i class="bi {{ $tierIcon }} me-1"></i> {{ $currentTier }} Member
                </div>
                <h6 class="fw-bold mb-0 text-white">Lihat Kartu Member & Diskon</h6>
            </div>
            <i class="bi bi-chevron-right text-white fs-4"></i>
        </a>

        {{-- INFORMASI AKUN --}}
        <div class="card-section">
            <div class="section-title"><i class="bi bi-person-fill"></i> Informasi Akun</div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="bi bi-at"></i></div>
                <div>
                    <div class="info-label">Username</div>
                    <div class="info-value">{{ '@' . ($user->username ?? '-') }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="bi bi-phone-fill"></i></div>
                <div>
                    <div class="info-label">No HP</div>
                    <div class="info-value">{{ $user->no_hp ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="bi bi-gender-ambiguous"></i></div>
                <div>
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $user->jenis_kelamin ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $user->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT BELANJA --}}
        <div class="card-section">
            <div class="section-title"><i class="bi bi-clock-history"></i> Riwayat Belanja</div>

            @if($riwayat->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-receipt fs-2 d-block mb-2"></i>
                    Belum ada transaksi.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-riwayat mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $trx)
                            <tr>
                                <td class="fw-semibold" style="font-size:0.8rem;">{{ $trx->kode_transaksi ?? '#'.$trx->id }}</td>
                                <td class="text-muted" style="font-size:0.8rem;">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</td>
                                <td class="fw-bold" style="color:#DD3827;">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($trx->status == 'selesai')
                                        <span class="badge-selesai"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                                    @elseif($trx->status == 'dikirim')
                                        <span class="badge-dikirim"><i class="bi bi-truck me-1"></i>Dikirim</span>
                                    @else
                                        <span class="badge-pending"><i class="bi bi-hourglass-split me-1"></i>Diproses</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <a href="{{ route('pembeli.riwayat') }}" class="text-danger small fw-semibold text-decoration-none">
                        Lihat semua riwayat <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>

    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
