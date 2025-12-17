<footer class="mt-auto pt-5 pb-4" style="background-color: #202124; color: #b0b0b0; border-top: 4px solid #E4002B;">
    <div class="container-fluid px-4">
        <div class="row gy-4">
            
            {{-- KOLOM 1: BRAND & ALAMAT --}}
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <div class="d-flex align-items-center mb-3">
                    {{-- Ganti src sesuai lokasi logo Anda --}}
                    <img src="{{ asset('template/img/tahu.png') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle bg-white p-1">
                    <span class="fs-4 fw-bold text-white text-uppercase" style="font-family: 'Oswald'; letter-spacing: 1px;">Warung Tahu</span>
                </div>
                <p class="small mb-2">
                    Menyajikan Tahu Lontong & Tahu Tek dengan resep bumbu petis legendaris sejak 2010. Rasakan kenikmatan asli Jawa Timur.
                </p>
                <ul class="list-unstyled text-small">
                    <li class="mb-1"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Jl. Sukorejo Indah No. 314, Katang</li>
                    <li class="mb-1"><i class="bi bi-clock-fill me-2 text-danger"></i> Buka: 10.00 - 16.00 WIB</li>
                    <li><i class="bi bi-envelope-fill me-2 text-danger"></i> admin@warungtahu.com</li>
                </ul>
            </div>

            {{-- KOLOM 2: MENU FAVORIT --}}
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-3 fw-bold" style="font-family: 'Oswald';">Menu Favorit</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none footer-link">Tahu Lontong Biasa</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none footer-link">Tahu Telur Spesial</a></li>
                    
                </ul>
            </div>

            {{-- KOLOM 3: LAYANAN --}}
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-3 fw-bold" style="font-family: 'Oswald';">Layanan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none footer-link">Dine-in (Makan di Tempat)</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none footer-link">Take Away (Bungkus)</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none footer-link">Menerima Pesanan (Catering)</a></li>
                </ul>
            </div>

            {{-- KOLOM 4: SOSIAL MEDIA & CTA --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white text-uppercase mb-3 fw-bold" style="font-family: 'Oswald';">Ikuti Kami</h5>
                <p class="small">Dapatkan promo terbaru dan info menarik lewat sosial media kami.</p>
                
                <div class="d-flex gap-2 mb-4">
                    <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-whatsapp"></i></a>
                </div>

                <div class="bg-dark p-3 rounded border border-secondary">
                    <h6 class="text-white mb-2">Pesan Cepat via WhatsApp?</h6>
                    <a href="https://wa.me/6285795813531" class="btn btn-success btn-sm w-100 fw-bold">
                        <i class="bi bi-whatsapp me-1"></i> Chat Sekarang
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="my-4 border-secondary">
        
        {{-- BAGIAN COPYRIGHT BAWAH --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start small">
                &copy; {{ date('Y') }} <strong>Warung Tahu Lontong</strong>. All Rights Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end small">
                <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-muted">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* CSS Khusus Footer (Simpan disini atau di master) */
    .footer-link {
        color: #b0b0b0;
        transition: 0.2s;
        font-size: 0.9rem;
    }
    .footer-link:hover {
        color: #E4002B; /* Merah KFC */
        padding-left: 5px;
    }
    
    .social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px; height: 35px;
        background-color: #333;
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: 0.3s;
    }
    .social-btn:hover {
        background-color: #E4002B;
        color: white;
        transform: translateY(-3px);
    }
</style>