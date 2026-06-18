<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Member - Smolie Gift</title>

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

        /* KARTU LOYALITAS */
        .loyalty-card {
            border-radius: 22px;
            padding: 28px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .loyalty-card::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .loyalty-card::after {
            content: '';
            position: absolute;
            bottom: -70px; left: -40px;
            width: 220px; height: 220px;
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
            width: 46px; height: 34px;
            background: rgba(255,255,255,0.25);
            border-radius: 6px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }
        .loyalty-level-badge {
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.2);
            padding: 5px 14px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .loyalty-progress {
            height: 10px;
            border-radius: 50px;
            background: rgba(255,255,255,0.25);
            overflow: hidden;
            margin-top: 18px;
        }
        .loyalty-progress-bar {
            height: 100%;
            border-radius: 50px;
            background: rgba(255,255,255,0.75);
            transition: width 0.6s ease;
        }

        /* CARD SECTION */
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

        /* TIER TABLE */
        .tier-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 10px;
            border: 1.5px solid #F1F5F9;
            transition: 0.2s;
        }
        .tier-row.active-tier {
            border-color: #DD3827;
            background: #FFF6F5;
        }
        .tier-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .tier-bronze-bg { background: linear-gradient(135deg, #CD7F32, #A0522D); }
        .tier-silver-bg { background: linear-gradient(135deg, #8E9EAB, #5B6E7C); }
        .tier-gold-bg { background: linear-gradient(135deg, #F7C83A, #D4A017); }
        .tier-platinum-bg { background: linear-gradient(135deg, #2C2C2C, #555); }
        .tier-name { font-weight: 700; font-size: 0.9rem; color: #2D3142; }
        .tier-desc { font-size: 0.78rem; color: #94A3B8; }
        .tier-discount {
            font-weight: 800;
            font-size: 1.1rem;
            color: #DD3827;
        }
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
                <span class="navbar-brand mb-0">Kartu Member</span>
            </div>
        </div>
    </nav>

    <div class="container pb-5" style="max-width: 720px;">

        @php
            $totalBelanja = $user->total_pembelanjaan ?? 0;
            $currentTier  = $user->level_member ?? 'Bronze';

            $tiers = [
                'Bronze'   => ['target' => 0,       'prev' => 0,       'diskon' => $diskonMap['Bronze']   ?? 0,  'icon' => 'bi-award',       'bg' => 'tier-bronze-bg',   'card' => 'loyalty-bronze'],
                'Silver'   => ['target' => 200000,  'prev' => 0,       'diskon' => $diskonMap['Silver']   ?? 5,  'icon' => 'bi-award-fill',  'bg' => 'tier-silver-bg',   'card' => 'loyalty-silver'],
                'Gold'     => ['target' => 500000,  'prev' => 200000,  'diskon' => $diskonMap['Gold']     ?? 10, 'icon' => 'bi-trophy',      'bg' => 'tier-gold-bg',     'card' => 'loyalty-gold'],
                'Platinum' => ['target' => 1000000, 'prev' => 500000,  'diskon' => $diskonMap['Platinum'] ?? 15, 'icon' => 'bi-trophy-fill', 'bg' => 'tier-platinum-bg', 'card' => 'loyalty-platinum'],
            ];

            $current = $tiers[$currentTier] ?? $tiers['Bronze'];
            $tierClass = $current['card'];
            $tierIcon  = $current['icon'];
            $diskon    = $current['diskon'];

            if ($currentTier == 'Bronze') { $nextTier = 'Silver'; $target = 200000; $prevTarget = 0; }
            elseif ($currentTier == 'Silver') { $nextTier = 'Gold'; $target = 500000; $prevTarget = 200000; }
            elseif ($currentTier == 'Gold') { $nextTier = 'Platinum'; $target = 1000000; $prevTarget = 500000; }
            else { $nextTier = 'Maksimal'; $target = 1000000; $prevTarget = 1000000; }

            if ($currentTier != 'Platinum') {
                $kurangBelanja = max(0, $target - $totalBelanja);
                $pembilang = max(0, $totalBelanja - $prevTarget);
                $penyebut  = $target - $prevTarget;
                $persen    = max(0, min(100, ($pembilang / $penyebut) * 100));
            } else {
                $kurangBelanja = 0;
                $persen = 100;
            }
        @endphp

        {{-- KARTU LOYALITAS VISUAL --}}
        <div class="loyalty-card {{ $tierClass }}">
            <div class="loyalty-chip"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="loyalty-level-badge">
                        <i class="bi {{ $tierIcon }} me-1"></i> {{ $currentTier }} Member
                    </div>
                    <h5 class="fw-bold mb-0 text-white">{{ $user->name }}</h5>
                    <p class="mb-0 small text-white opacity-80">Kartu Loyalitas Smolie Gift</p>
                </div>
                <div class="text-end">
                    @if($diskon > 0)
                        <div class="text-white" style="font-size: 0.75rem; opacity: 0.8;">Diskon Member</div>
                        <div class="text-white fw-bold" style="font-size: 1.5rem;">{{ $diskon }}%</div>
                    @else
                        <div class="text-white" style="font-size: 0.75rem; opacity: 0.8;">Mulai belanja</div>
                        <div class="text-white fw-bold" style="font-size: 1.2rem;">Bronze</div>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <div class="d-flex justify-content-between text-white small mb-1">
                    <span style="opacity:0.8;">Total Belanja</span>
                    <span class="fw-bold">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>
                <div class="loyalty-progress">
                    <div class="loyalty-progress-bar" style="width: {{ $persen }}%;"></div>
                </div>
                <div class="mt-2 text-white small">
                    @if($currentTier != 'Platinum')
                        <i class="bi bi-lightning-charge-fill me-1" style="opacity:0.9;"></i>
                        Belanja <strong>Rp {{ number_format($kurangBelanja, 0, ',', '.') }}</strong> lagi → naik ke <strong>{{ $nextTier }}</strong>
                    @else
                        <i class="bi bi-trophy-fill me-1"></i> Level tertinggi! Nikmati diskon {{ $diskon }}% di setiap pesanan.
                    @endif
                </div>
            </div>
        </div>

        {{-- DAFTAR LEVEL & BENEFIT --}}
        <div class="card-section">
            <div class="section-title"><i class="bi bi-list-stars"></i> Level Member & Diskon</div>

            @foreach($tiers as $name => $t)
            <div class="tier-row {{ $currentTier == $name ? 'active-tier' : '' }}">
                <div class="tier-icon {{ $t['bg'] }}">
                    <i class="bi {{ $t['icon'] }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="tier-name">{{ $name }}
                        @if($currentTier == $name)
                            <span class="badge bg-danger ms-1" style="font-size:0.65rem;">Level Anda</span>
                        @endif
                    </div>
                    <div class="tier-desc">
                        @if($name == 'Bronze')
                            Member baru
                        @else
                            Min. belanja Rp {{ number_format($t['prev'], 0, ',', '.') }}
                        @endif
                    </div>
                </div>
                <div class="tier-discount">{{ $t['diskon'] }}%</div>
            </div>
            @endforeach

            <p class="text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle me-1"></i> Diskon dihitung otomatis dari total harga setiap kali checkout sesuai level member kamu.
            </p>
        </div>

    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
