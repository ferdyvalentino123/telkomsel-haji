<x-Sales.SalesLayouts>
<style>
    .page-header {
        background: linear-gradient(135deg, #bc0007 0%, #e2241d 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(188, 0, 7, 0.2);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l2.5 12.5L45 15l-12.5 2.5L30 30l-2.5-12.5L15 15l12.5-2.5L30 0zm0 30l2.5 12.5L45 45l-12.5 2.5L30 60l-2.5-12.5L15 45l12.5-2.5L30 30z' fill='%23ffffff' fill-opacity='0.05'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }
    .page-header > * { position: relative; z-index: 1; }

    .stat-mini {
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 12px 20px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        min-width: 110px;
    }
    .stat-mini .num { font-size: 1.8rem; font-weight: 800; }
    .stat-mini .label { font-size: 0.75rem; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }

    .filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e8e8e8;
    }

    .trx-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e8e8e8;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .trx-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.09); transform: translateY(-1px); }
    .trx-card.is-done { border-left: 4px solid #10b981; }
    .trx-card.is-pending { border-left: 4px solid #f59e0b; }

    .trx-body { padding: 18px 22px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        margin-top: 14px;
    }
    .info-item { display: flex; flex-direction: column; }
    .info-item .lbl { font-size: 0.73rem; color: #888; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px; }
    .info-item .val { font-size: 0.92rem; font-weight: 600; color: #1a1c1c; }

    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;
    }
    .badge-pending { background: #fff8e1; color: #b7791f; border: 1px solid #f6d860; }
    .badge-done { background: #e8f5e9; color: #1a7a4a; border: 1px solid #a5d6a7; }

    .btn-upload {
        background: linear-gradient(135deg, #bc0007, #e2241d);
        color: #fff; border: none; padding: 9px 20px;
        border-radius: 8px; font-weight: 600; font-size: 0.88rem;
        cursor: pointer; transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-upload:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }

    .btn-view-bukti {
        background: #e3f2fd; color: #0d47a1;
        border: 1px solid #bbdefb; padding: 7px 16px;
        border-radius: 8px; font-weight: 600; font-size: 0.85rem;
        cursor: pointer; transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none;
    }
    .btn-view-bukti:hover { background: #0d47a1; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; background: #fff; border-radius: 14px; }
    .empty-state i { font-size: 3.5rem; color: #e0e0e0; margin-bottom: 14px; }

    @media (max-width: 576px) {
        .info-grid { grid-template-columns: 1fr 1fr; }
        .page-header { padding: 18px; }
        .stat-mini { min-width: 90px; }
    }
</style>

<div class="container-fluid p-4">

    {{-- Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="text-white" style="font-size:1.6rem; font-weight:800; margin:0;">
                    <i class="fas fa-sim-card me-2"></i> Aktivasi Pelanggan Online
                </h1>
                <p style="margin:4px 0 0; opacity:0.85; font-size:0.9rem;">
                    Upload bukti injeksi untuk transaksi yang sudah lunas
                </p>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div class="stat-mini">
                    <div class="num">{{ $totalPending }}</div>
                    <div class="label">Menunggu</div>
                </div>
                <div class="stat-mini" style="background: rgba(16,185,129,0.25);">
                    <div class="num">{{ $totalDone }}</div>
                    <div class="label">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('sales.aktivasi.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Cari Pelanggan</label>
                <input type="text" name="search" class="form-control" placeholder="Nama / No. HP / ID Transaksi"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Tanggal Aktivasi</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Aktif</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Sudah Aktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger flex-fill">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('sales.aktivasi.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- List --}}
    @if($transaksis->isEmpty())
        <div class="empty-state">
            <i class="fas fa-check-circle d-block" style="color: #10b981;"></i>
            <h5 class="text-muted mt-2">Tidak ada transaksi yang ditemukan.</h5>
            <p class="text-muted small">Coba ubah filter atau belum ada transaksi pelanggan online yang lunas.</p>
        </div>
    @else
        @foreach($transaksis as $trx)
            <div class="trx-card {{ $trx->is_activated ? 'is-done' : 'is-pending' }}">
                <div class="trx-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        {{-- Info utama --}}
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge-status {{ $trx->is_activated ? 'badge-done' : 'badge-pending' }}">
                                    @if($trx->is_activated)
                                        <i class="fas fa-check-circle"></i> AKTIF
                                    @else
                                        <i class="fas fa-hourglass-half"></i> MENUNGGU AKTIVASI
                                    @endif
                                </span>
                                <span class="text-muted small">#{{ $trx->id_transaksi }}</span>
                            </div>
                            <div style="font-size: 1.1rem; font-weight: 700; color: #1a1c1c;">
                                {{ $trx->nama_pelanggan }}
                            </div>
                            <div class="text-muted small">{{ $trx->telepon_pelanggan }}</div>
                        </div>

                        {{-- Aksi --}}
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            @if($trx->is_activated && $trx->bukti_injeksi)
                                <a href="{{ asset('storage/' . $trx->bukti_injeksi) }}" target="_blank" class="btn-view-bukti">
                                    <i class="fas fa-image"></i> Lihat Bukti
                                </a>
                            @endif
                            <button class="btn-upload btn-open-upload"
                                data-id="{{ $trx->id }}"
                                data-nama="{{ $trx->nama_pelanggan }}"
                                data-paket="{{ $trx->produk->produk_nama ?? '-' }}">
                                <i class="fas fa-upload"></i>
                                {{ $trx->is_activated ? 'Ganti Bukti' : 'Upload Bukti Injeksi' }}
                            </button>
                        </div>
                    </div>

                    {{-- Detail info grid --}}
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="lbl">Paket</span>
                            <span class="val">{{ $trx->produk->produk_nama ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Tanggal Aktivasi</span>
                            <span class="val text-primary">
                                {{ $trx->aktivasi_tanggal ? \Carbon\Carbon::parse($trx->aktivasi_tanggal)->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">No. Telepon Injeksi</span>
                            <span class="val">{{ $trx->telepon_pelanggan }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Total Bayar</span>
                            <span class="val text-danger">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Tanggal Transaksi</span>
                            <span class="val">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="lbl">Metode</span>
                            <span class="val">{{ $trx->metode_pembayaran ?? 'QRIS/Midtrans' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

{{-- Modal Upload (SweetAlert2) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.btn-open-upload').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const nama  = this.dataset.nama;
            const paket = this.dataset.paket;

            Swal.fire({
                title: '<i class="fas fa-upload me-2 text-danger"></i> Upload Bukti Injeksi',
                html: `
                    <div style="text-align:left; font-size:0.9rem;">
                        <div class="mb-3">
                            <p class="mb-1 text-muted">Pelanggan: <strong class="text-dark">${nama}</strong></p>
                            <p class="mb-0 text-muted">Paket: <strong class="text-dark">${paket}</strong></p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Pilih File Bukti Injeksi <span class="text-danger">*</span></label>
                            <input type="file" id="bukti_file" accept=".jpg,.jpeg,.png,.pdf"
                                class="form-control" style="border-radius:8px;">
                            <small class="text-muted">Format: JPG, PNG, atau PDF. Maks. 4MB.</small>
                        </div>
                        <div id="preview-container" class="mt-2 d-none">
                            <img id="img-preview" src="" style="max-width:100%; border-radius:8px; border:1px solid #eee;">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#bc0007',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-upload me-1"></i> Upload & Aktifkan',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                preConfirm: () => {
                    const file = document.getElementById('bukti_file').files[0];
                    if (!file) {
                        Swal.showValidationMessage('File bukti injeksi wajib dipilih!');
                        return false;
                    }
                    if (file.size > 4 * 1024 * 1024) {
                        Swal.showValidationMessage('Ukuran file terlalu besar. Maksimal 4MB.');
                        return false;
                    }
                    return file;
                },
                didOpen: () => {
                    document.getElementById('bukti_file').addEventListener('change', function () {
                        const file = this.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = e => {
                                document.getElementById('img-preview').src = e.target.result;
                                document.getElementById('preview-container').classList.remove('d-none');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            document.getElementById('preview-container').classList.add('d-none');
                        }
                    });
                }
            }).then(result => {
                if (!result.isConfirmed) return;

                const formData = new FormData();
                formData.append('bukti_injeksi', result.value);
                formData.append('_token', csrfToken);

                Swal.fire({
                    title: 'Mengupload...',
                    html: '<div class="d-flex justify-content-center align-items-center py-3"><div class="spinner-border text-danger"></div></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                fetch(`/programhaji/sales/aktivasi/${id}/upload`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Upload Berhasil!',
                            html: `
                                <p class="mb-3">${data.message}</p>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="${data.wa_link}" target="_blank"
                                       class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3 fw-semibold"
                                       style="background:#25D366; border:none;">
                                        <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Kirim ke WA Pelanggan
                                    </a>
                                </div>
                                <p class="text-muted mt-3 small"><i class="fas fa-info-circle me-1"></i> Klik tombol di atas untuk membuka WhatsApp dan kirim bukti ke pelanggan.</p>
                            `,
                            confirmButtonColor: '#bc0007',
                            confirmButtonText: 'Selesai',
                            showConfirmButton: true,
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error'));
            });
        });
    });
});
</script>

</x-Sales.SalesLayouts>
