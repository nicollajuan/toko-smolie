{{-- 
    EXAMPLE: Cara menggunakan AdminHelper di Blade Templates
    
    File ini menunjukkan berbagai cara menggunakan helper untuk menampilkan
    informasi kontak admin di berbagai tempat di website.
--}}

<h2>Contoh Penggunaan AdminHelper</h2>

{{-- =========================================================================
    1. TAMPILKAN NOMOR WHATSAPP ADMIN SIMPLE
    ========================================================================= --}}
<div class="example-1">
    <h4>1. Tampilkan Nomor WhatsApp Sederhana</h4>
    
    @if($adminWhatsApp)
        <p>WhatsApp Admin: <strong>{{ $adminWhatsApp }}</strong></p>
    @else
        <p class="text-muted">Admin belum menambahkan nomor WhatsApp</p>
    @endif
</div>

{{-- =========================================================================
    2. TAMPILKAN LINK WHATSAPP (YANG PALING SERING DIGUNAKAN)
    ========================================================================= --}}
<div class="example-2 mt-4">
    <h4>2. Link WhatsApp untuk Chat (Paling Direkomendasikan)</h4>
    
    @php
        $whatsappLink = \App\Helpers\AdminHelper::getAdminWhatsAppLink('Halo, saya ingin bertanya tentang produk Anda');
    @endphp
    
    @if($whatsappLink)
        <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-success">
            <i class="bi bi-whatsapp"></i> Chat Admin di WhatsApp
        </a>
    @else
        <p class="text-muted">Admin belum menyiapkan kontak WhatsApp</p>
    @endif
</div>

{{-- =========================================================================
    3. TAMPILKAN INFO KONTAK LENGKAP
    ========================================================================= --}}
<div class="example-3 mt-4">
    <h4>3. Info Kontak Admin Lengkap</h4>
    
    @if($adminContactInfo['name'])
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $adminContactInfo['name'] }}</h5>
                
                @if($adminContactInfo['email'])
                    <p><i class="bi bi-envelope"></i> Email: {{ $adminContactInfo['email'] }}</p>
                @endif
                
                @if($adminContactInfo['whatsapp'])
                    <p><i class="bi bi-whatsapp"></i> WhatsApp: 
                        <a href="{{ $adminContactInfo['whatsapp_link'] }}" target="_blank">
                            {{ $adminContactInfo['whatsapp'] }}
                        </a>
                    </p>
                @endif
                
                @if($adminContactInfo['no_hp'])
                    <p><i class="bi bi-telephone"></i> Telepon: {{ $adminContactInfo['no_hp'] }}</p>
                @endif
                
                @if($adminContactInfo['alamat'])
                    <p><i class="bi bi-geo-alt"></i> Alamat: {{ $adminContactInfo['alamat'] }}</p>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- =========================================================================
    4. FLOATING CONTACT BUTTON (CONTOH UNTUK HALAMAN PEMBELI)
    ========================================================================= --}}
<div class="example-4 mt-4">
    <h4>4. Floating Contact Button</h4>
    
    @if(\App\Helpers\AdminHelper::hasAdminWhatsApp())
        <div style="position: fixed; bottom: 20px; right: 20px; z-index: 999;">
            <a href="{{ \App\Helpers\AdminHelper::getAdminWhatsAppLink('Halo, ada yang bisa saya tanyakan?') }}" 
               target="_blank" 
               class="btn btn-success rounded-circle" 
               style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>
    @endif
</div>

{{-- =========================================================================
    5. MENGGUNAKAN DI CONTROLLER & PASSING KE VIEW
    ========================================================================= --}}
{{-- 
    # Contoh di Controller:
    
    use App\Helpers\AdminHelper;
    
    public function index()
    {
        return view('pembeli.home', [
            'adminWhatsApp' => AdminHelper::getAdminWhatsApp(),
            'adminContactInfo' => AdminHelper::getAdminContactInfo(),
        ]);
    }
    
    # Atau kirim helper langsung:
    
    public function index()
    {
        return view('pembeli.home', [
            'adminHelper' => AdminHelper::class,
        ]);
    }
    
    # Di Blade:
    
    {{ $adminHelper::getAdminWhatsApp() }}
--}}

{{-- =========================================================================
    6. CONTOH KARTU KONTAK DENGAN STYLING BOOTSTRAP
    ========================================================================= --}}
<div class="example-6 mt-4">
    <h4>6. Kartu Kontak dengan Styling</h4>
    
    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-headset"></i> Hubungi Admin Smolie Gift</h5>
        </div>
        <div class="card-body text-center">
            @if($adminContactInfo['foto'])
                <img src="{{ asset('img/user/' . $adminContactInfo['foto']) }}" 
                     alt="Admin" 
                     class="rounded-circle mb-3" 
                     style="width: 100px; height: 100px; object-fit: cover;">
            @endif
            
            <h5 class="card-title">{{ $adminContactInfo['name'] ?? 'Admin Smolie' }}</h5>
            
            @if($adminContactInfo['whatsapp_link'])
                <a href="{{ $adminContactInfo['whatsapp_link'] }}" 
                   target="_blank" 
                   class="btn btn-success btn-lg d-block mt-3 mb-2">
                    <i class="bi bi-whatsapp me-2"></i> Chat via WhatsApp
                </a>
            @endif
            
            @if($adminContactInfo['email'])
                <a href="mailto:{{ $adminContactInfo['email'] }}" class="btn btn-outline-secondary d-block">
                    <i class="bi bi-envelope me-2"></i> Email
                </a>
            @endif
        </div>
    </div>
</div>

{{-- =========================================================================
    7. MENAMPILKAN DI NAVBAR / HEADER
    ========================================================================= --}}
<div class="example-7 mt-4">
    <h4>7. Di Navbar / Header</h4>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <span class="navbar-brand">Smolie Gift</span>
            <div class="d-flex align-items-center">
                @if($adminContactInfo['whatsapp_link'])
                    <a href="{{ $adminContactInfo['whatsapp_link'] }}" 
                       target="_blank" 
                       class="btn btn-sm btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp"></i>
                        Chat Admin
                    </a>
                @endif
            </div>
        </div>
    </nav>
</div>

{{-- =========================================================================
    8. DI FOOTER
    ========================================================================= --}}
<div class="example-8 mt-4">
    <h4>8. Di Footer</h4>
    
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Kontak Kami</h5>
                    @if($adminContactInfo['whatsapp_link'])
                        <p>
                            <a href="{{ $adminContactInfo['whatsapp_link'] }}" 
                               target="_blank" 
                               class="text-decoration-none text-success fw-bold">
                                <i class="bi bi-whatsapp"></i> 
                                {{ $adminContactInfo['whatsapp'] }}
                            </a>
                        </p>
                    @endif
                    
                    @if($adminContactInfo['email'])
                        <p>
                            <a href="mailto:{{ $adminContactInfo['email'] }}" 
                               class="text-decoration-none text-info">
                                <i class="bi bi-envelope"></i>
                                {{ $adminContactInfo['email'] }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</div>
