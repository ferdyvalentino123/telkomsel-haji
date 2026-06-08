@extends("travel.layout")
@section("title", "Pembayaran Kolektif")

@section("css")
<style>
    :root {
        --primary: #bc0007;
        --primary-dark: #8a0005;
        --primary-light: #ec1d24;
    }
    .payment-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }
    .payment-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 1.5rem;
    }
    .btn-pay {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(188, 0, 7, 0.3);
        color: white;
    }
    .btn-pay:disabled {
        background: #ccc;
        box-shadow: none;
        cursor: not-allowed;
    }
    .msisdn-list {
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    .msisdn-item {
        padding: 8px 12px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .msisdn-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section("content")
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card payment-card">
                <div class="payment-header text-center">
                    <h3 class="mb-1 text-white font-weight-bold"><i class="fas fa-shield-alt mr-2"></i> Pembayaran Kolektif</h3>
                    <p class="mb-0 opacity-80">Selesaikan transaksi menggunakan Midtrans</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4 text-center">
                        <div class="text-muted small uppercase font-weight-bold">Total Pembayaran</div>
                        <h1 class="display-4 font-weight-bold text-danger my-2">Rp {{ number_format($totalHarga, 0, ',', '.') }}</h1>
                        <span class="badge badge-warning px-3 py-2 font-weight-bold"><i class="fas fa-clock mr-1"></i> MENUNGGU PEMBAYARAN</span>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-muted mr-2"></i> Detail Pesanan</h5>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Paket</div>
                            <div class="col-6 text-right font-weight-bold">{{ $transaksi->produk->produk_nama }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Harga Satuan</div>
                            <div class="col-6 text-right font-weight-bold">Rp {{ number_format($transaksi->produk->produk_harga_akhir, 0, ',', '.') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Jumlah Jamaah</div>
                            <div class="col-6 text-right font-weight-bold">{{ $grupTransaksi->count() }} Orang</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-users text-muted mr-2"></i> Daftar MSISDN / No. HP</h5>
                        <div class="msisdn-list">
                            @foreach($grupTransaksi as $index => $item)
                                <div class="msisdn-item">
                                    <span class="text-dark font-weight-bold">{{ $index + 1 }}. {{ $item->nomor_telepon }}</span>
                                    <span class="badge badge-light text-muted">ID: {{ $item->id_transaksi }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <button id="pay-button" class="btn btn-pay btn-block btn-lg">
                            <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('travel.transaksi.index') }}" class="text-muted small">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<!-- Load Midtrans Snap.js -->
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        const snapToken = '{{ $transaksi->snap_token }}';

        function openSnap() {
            if (!snapToken) {
                Swal.fire({
                    title: 'Error',
                    text: 'Token pembayaran tidak valid. Silakan hubungi admin.',
                    icon: 'error'
                });
                return;
            }

            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Pembayaran berhasil! Mengalihkan ke riwayat transaksi...',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("travel.transaksi.index") }}';
                    });
                },
                onPending: function(result) {
                    Swal.fire({
                        title: 'Menunggu Pembayaran',
                        text: 'Silakan selesaikan pembayaran Anda.',
                        icon: 'info',
                        confirmButtonText: 'Tutup'
                    }).then(() => {
                        window.location.href = '{{ route("travel.transaksi.index") }}';
                    });
                },
                onError: function(result) {
                    Swal.fire({
                        title: 'Gagal / Kedaluwarsa',
                        text: 'Transaksi Anda gagal atau telah kedaluwarsa. Silakan lakukan pemesanan ulang.',
                        icon: 'error',
                        confirmButtonText: 'Kembali'
                    }).then(() => {
                        window.location.href = '{{ route("travel.transaksi.index") }}';
                    });
                },
                onClose: function() {
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
                }
            });
        }

        // Auto trigger on page load
        setTimeout(openSnap, 500);

        // Fallback click listener
        payButton.addEventListener('click', openSnap);
    });
</script>
@endsection
