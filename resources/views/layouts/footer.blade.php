{{--
    FOOTER TUNGGAL — @include('layouts.footer')
    Pakai ini di view pembeli (index, riwayat, dll).
    JANGAN di-include di layouts/master.blade.php.
--}}

<footer style="background-color: #202124; color: #b0b0b0; font-family: 'Nunito', sans-serif;">
    <div style="height: 5px; background-color: #E4002B; width: 100%;"></div>

    <div class="container py-5">
        <div class="row g-4">

            {{-- KOLOM 1: BRAND --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-family: 'Poppins', sans-serif;">
                    <img src="{{ asset('template/img/smolie.jpg') }}" alt="Smolie Gift" width="45" height="45"
                         class="me-2 rounded-circle border border-2 border-white shadow-sm object-fit-cover">
                    SMOLIE GIFT
                </h5>
                <p class="small mb-4" style="line-height: 1.7;">
                    Pusat pemesanan custom souvenir eksklusif, terpercaya, dan harga bersahabat
                    untuk menyempurnakan hari bahagia Anda.
                </p>
                <ul class="list-unstyled small">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill me-2 mt-1" style="color:#E4002B;"></i>
                        <span>Surabaya, Jawa Timur</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-clock-fill me-2" style="color:#E4002B;"></i>
                        <span>Buka: Senin – Sabtu, 08.00 – 16.00 WIB</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2" style="color:#E4002B;"></i>
                        <span>admin@smoliegift.com</span>
                    </li>
                </ul>
            </div>

            {{-- KOLOM 2: MENU --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 text-uppercase small" style="font-family: 'Poppins', sans-serif;">Menu Favorit</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ url('/') }}#katalog" class="text-decoration-none footer-link">Gantungan Kunci</a></li>
                    <li class="mb-2"><a href="{{ url('/') }}#katalog" class="text-decoration-none footer-link">Piring Custom</a></li>
                    <li class="mb-2"><a href="{{ url('/') }}#katalog" class="text-decoration-none footer-link">Mug Custom</a></li>
                    <li><a href="{{ url('/') }}" class="text-decoration-none footer-link">Lihat Semua</a></li>
                </ul>
            </div>

            {{-- KOLOM 3: LAYANAN --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 text-uppercase small" style="font-family: 'Poppins', sans-serif;">Layanan</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">Pesan Ambil (COD)</li>
                    <li class="mb-2">Pesan Antar</li>
                    <li>Custom Desain</li>
                </ul>
            </div>

            {{-- KOLOM 4: SOSMED & WA --}}
            <div class="col-lg-4 col-md-12">
                <h6 class="text-white fw-bold mb-3 text-uppercase small" style="font-family: 'Poppins', sans-serif;">Ikuti Kami</h6>
                <p class="small mb-3">Dapatkan promo terbaru dan info menarik lewat sosial media kami.</p>
                <div class="d-flex gap-2 mb-4">
                    {{-- Ganti href="#" dengan URL akun sosmed yang nyata --}}
                </div>
                <a href="https://wa.me/{{ $site_whatsapp ?? '' }}" target="_blank"
                   class="btn w-100 fw-bold rounded-pill py-2 text-white shadow-sm"
                   style="background-color:#E4002B; border:none; font-family:'Poppins',sans-serif;">
                    <i class="bi bi-whatsapp me-2"></i> CHAT WHATSAPP KAMI
                </a>
            </div>

        </div>
    </div>

    <div class="py-3 text-center border-top" style="border-color:#2e2e2e !important; background-color:#1a1a1a;">
        <small style="color:#888;">
            &copy; {{ date('Y') }} <strong style="color:#fff;">Smolie Gift</strong>. All Rights Reserved.
        </small>
    </div>
</footer>

<style>
.footer-link { color: #b0b0b0; transition: color 0.2s, padding-left 0.2s; }
.footer-link:hover { color: #E4002B; padding-left: 5px; }
.footer-social-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; background-color: #333; color: #fff;
    border-radius: 50%; text-decoration: none; font-size: 1rem;
    transition: background-color 0.3s, transform 0.3s;
}
.footer-social-btn:hover { background-color: #E4002B; color: #fff; transform: translateY(-3px); }
</style>