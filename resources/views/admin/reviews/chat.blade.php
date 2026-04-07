@extends('layouts.master')

@section('content')
<div class="container-fluid pt-3">
    <div class="row chat-container shadow-sm bg-white rounded-4 overflow-hidden" style="height: 85vh; border: 1px solid #eee;">
        
        {{-- BAGIAN KIRI: DAFTAR KONTAK --}}
        <div class="col-md-4 col-lg-3 p-0 border-end d-flex flex-column h-100 bg-white">
            <div class="p-3 border-bottom bg-light">
                <h5 class="fw-bold mb-3" style="color: #DD3827; font-family: 'Poppins', sans-serif;">Pesan Masuk</h5>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0 shadow-none" placeholder="Cari nama pelanggan...">
                </div>
            </div>

            <div class="contact-list flex-grow-1 overflow-auto custom-scrollbar">
                @forelse($kontak as $k)
                <div class="contact-item p-3 border-bottom d-flex align-items-center cursor-pointer hover-bg" id="contact-{{ $k->id }}" onclick="pilihKontak({{ $k->id }}, '{{ $k->name }}')">
                    @if($k->foto && file_exists(public_path('img/user/'.$k->foto)))
                        <img src="{{ asset('img/user/'.$k->foto) }}" class="rounded-circle me-3 object-fit-cover" width="45" height="45">
                    @else
                        <div class="text-white rounded-circle me-3 d-flex justify-content-center align-items-center fw-bold shadow-sm" style="width: 45px; height: 45px; background-color: #DD3827; font-family: 'Poppins', sans-serif;">
                            {{ strtoupper(substr($k->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-truncate" style="font-family: 'Poppins', sans-serif; font-size: 0.95rem;">{{ $k->name }}</strong>
                        </div>
                        <div class="text-muted text-truncate small"><i class="bi bi-chat-text text-secondary me-1"></i> Klik untuk balas pesan</div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted d-flex flex-column align-items-center">
                    <i class="bi bi-inbox fs-1 mb-2 opacity-50"></i>
                    <small>Belum ada pesan masuk.</small>
                </div>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN KANAN: RUANG OBROLAN --}}
        <div class="col-md-8 col-lg-9 p-0 d-flex flex-column h-100" style="background-color: #F9F9FB;">
            <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between shadow-sm" style="z-index: 10;">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary text-white rounded-circle me-3 d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;" id="chat-header-avatar">?</div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;" id="chat-header-nama">Pilih Pelanggan</h6>
                        <small class="text-muted" id="chat-header-status">Pilih kontak di sebelah kiri untuk memulai obrolan</small>
                    </div>
                </div>
                <button class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-three-dots-vertical"></i></button>
            </div>

            <div id="admin-chat-history" class="chat-history flex-grow-1 p-4 overflow-auto custom-scrollbar d-flex flex-column gap-3">
                <div class="h-100 d-flex justify-content-center align-items-center flex-column text-muted opacity-50">
                    <i class="bi bi-chat-dots fs-1 mb-2"></i>
                    <p>Ruang Obrolan Kosong</p>
                </div>
            </div>

            <div class="p-3 bg-white border-top">
                <div class="input-group align-items-center bg-light p-1 rounded-pill border">
                    <button class="btn border-0 text-muted"><i class="bi bi-emoji-smile fs-5"></i></button>
                    <button class="btn border-0 text-muted"><i class="bi bi-paperclip fs-5"></i></button>
                    
                    <input type="hidden" id="active-user-id" value="">
                    <input type="text" id="admin-input-pesan" class="form-control border-0 bg-transparent shadow-none" placeholder="Tulis pesan..." disabled>
                    <button id="admin-btn-kirim" class="btn rounded-pill text-white px-4 fw-bold shadow-sm" style="background-color: #DD3827; margin: 2px;" disabled>
                        Kirim <i class="bi bi-send ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-bg:hover { background-color: #f8f9fa; }
    .active-chat { background-color: #FDE8E5 !important; border-left: 4px solid #DD3827 !important; }
    .chat-bubble { max-width: 90%; position: relative; }
    .chat-bubble.left { border-bottom-left-radius: 4px !important; }
    .chat-bubble.right { border-bottom-right-radius: 4px !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>

<script>
    // Variabel untuk menyimpan jumlah pesan terakhir agar tidak berkedip saat refresh
    let lastMessageCount = 0;

    // --- FUNGSI KLIK KONTAK ---
    function pilihKontak(userId, userName) {
        document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active-chat'));
        document.getElementById('contact-' + userId).classList.add('active-chat');

        document.getElementById('chat-header-nama').innerText = userName;
        document.getElementById('chat-header-status').innerHTML = '<i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i>Sedang Online';
        document.getElementById('chat-header-avatar').innerText = userName.charAt(0).toUpperCase();
        
        document.getElementById('active-user-id').value = userId;
        document.getElementById('admin-input-pesan').disabled = false;
        document.getElementById('admin-btn-kirim').disabled = false;
        document.getElementById('admin-input-pesan').focus();

        let chatHistory = document.getElementById('admin-chat-history');
        chatHistory.innerHTML = `<div class="text-center my-2"><span class="badge bg-light text-muted fw-normal px-3 py-1 border">Memuat pesan...</span></div>`;

        // Reset hitungan pesan saat pindah kontak
        lastMessageCount = 0;
        refreshPesanOtomatis(userId, userName, true);
    }

    // --- FUNGSI REFRESH PESAN (REAL-TIME) ---
    function refreshPesanOtomatis(userId, userName, isFirstLoad = false) {
        fetch(`/admin/chat/messages/${userId}`)
            .then(response => response.json())
            .then(messages => {
                // Hanya update DOM jika ada pesan baru untuk mencegah flickering
                if (messages.length !== lastMessageCount) {
                    let chatHistory = document.getElementById('admin-chat-history');
                    let htmlContent = `<div class="text-center my-2"><span class="badge bg-light text-muted fw-normal px-3 py-1 border">Riwayat Obrolan</span></div>`;

                    if(messages.length === 0) {
                        htmlContent += `<div class="text-center text-muted my-3"><small>Belum ada obrolan.</small></div>`;
                    } else {
                        messages.forEach(msg => {
                            let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            if(msg.pengirim === 'pembeli') {
                                htmlContent += `
                                <div class="d-flex align-items-end w-75 mb-3">
                                    <div class="bg-secondary text-white rounded-circle me-2 mb-1 d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; font-size:12px;">${userName.charAt(0).toUpperCase()}</div>
                                    <div class="chat-bubble left bg-white border p-3 rounded-4 shadow-sm">
                                        <p class="mb-1 text-dark">${msg.pesan}</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">${time}</small>
                                    </div>
                                </div>`;
                            } else {
                                htmlContent += `
                                <div class="d-flex align-items-end justify-content-end w-100 mb-3">
                                    <div class="chat-bubble right p-3 rounded-4 shadow-sm text-white" style="background-color: #DD3827;">
                                        <p class="mb-1">${msg.pesan}</p>
                                        <small class="text-white-50" style="font-size: 0.7rem;">${time} <i class="bi bi-check2-all ms-1"></i></small>
                                    </div>
                                </div>`;
                            }
                        });
                    }
                    
                    chatHistory.innerHTML = htmlContent;
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                    lastMessageCount = messages.length;
                }
            });
    }

    // Polling setiap 5 detik
    setInterval(function() {
        let userId = document.getElementById('active-user-id').value;
        let userName = document.getElementById('chat-header-nama').innerText;
        if (userId) {
            refreshPesanOtomatis(userId, userName);
        }
    }, 5000);

    // --- FUNGSI KIRIM BALASAN ---
    function kirimPesanAdmin() {
        let userId = document.getElementById('active-user-id').value;
        let pesanInput = document.getElementById('admin-input-pesan');
        let pesanTeks = pesanInput.value;

        if (pesanTeks.trim() === '' || userId === '') return;

        fetch('/chat/kirim', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                pesan: pesanTeks,
                pengirim: 'admin'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                pesanInput.value = '';
                refreshPesanOtomatis(userId, document.getElementById('chat-header-nama').innerText);
            } else {
                alert("Gagal mengirim pesan: " + data.message);
            }
        });
    }

    document.getElementById('admin-btn-kirim').addEventListener('click', kirimPesanAdmin);
    document.getElementById('admin-input-pesan').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') kirimPesanAdmin();
    });
</script>
@endsection