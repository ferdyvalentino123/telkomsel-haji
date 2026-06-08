@extends("travel.layout")
@section("title", "Booking Paket")

@section("css")
<style>
    :root { --primary: #bc0007; --primary-dark: #8a0005; --primary-light: #ec1d24; }
    .card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .btn-tsel { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 600; }
    .btn-tsel:hover { box-shadow: 0 5px 15px rgba(188, 0, 7, 0.3); color: white; transform: translateY(-1px); }
    .table thead th { background-color: #f8f9fa; border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #888; }
    .status-badge { padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-booked { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
</style>
@endsection

@section("content")
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold mb-0">Booking Paket Perjalanan</h2>
            <p class="text-muted">Kelola reservasi pelanggan Anda</p>
        </div>
        <button class="btn btn-tsel" data-toggle="modal" data-target="#modalBooking">
            <i class="fas fa-plus mr-2"></i> Buat Booking Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">ID Booking</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Tgl Aktivasi</th>
                            <th>Status</th>
                            <th class="text-right pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="pl-4 font-weight-bold">{{ $booking->id_transaksi }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ $booking->nama_pelanggan }}</div>
                                    <small class="text-muted">{{ $booking->nomor_telepon }}</small>
                                </td>
                                <td>{{ $booking->produk->produk_nama ?? 'Paket Tidak Tersedia' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->aktivasi_tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge status-booked">Booked</span>
                                </td>
                                <td class="text-right pr-4">
                                    <a href="{{ route('travel.transaksi.show', $booking->id) }}" class="btn btn-sm btn-light border">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="{{ asset('admin_asset/img/photos/no_data.png') }}" alt="No Data" style="height: 100px; opacity: 0.5;">
                                    <p class="mt-3 text-muted">Belum ada booking aktif.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Booking -->
<div class="modal fade" id="modalBooking" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold">Form Booking Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('travel.booking.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Paket Perjalanan</label>
                        <select name="produk_id" class="form-control" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->produk_nama }} - Rp {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nomor MSISDN (HP)</label>
                        <input type="text" name="nomor_telepon" class="form-control" placeholder="0812xxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Aktivasi</label>
                        <input type="date" name="aktivasi_tanggal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tsel px-4">Simpan Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

