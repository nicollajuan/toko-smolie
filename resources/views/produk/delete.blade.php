<div class="modal fade" id="modalHapusProduk{{ $data->id }}" tabindex="-1"
aria-labelledby="modalHapusProdukLabel{{ $data->id }}" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalHapusProdukLabel{{ $data->id }}">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            Yakin untuk menghapus produk <strong>{{ $data->nama_produk }}</strong>?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form action="{{ route('produk.destroy', $data->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
</div>