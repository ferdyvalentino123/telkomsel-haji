<x-supvis.SupvisLayouts>
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title mb-0"><i class="fas fa-box"></i> Daftar Produk</h1>
                <p class="text-muted mt-2">Lihat informasi semua produk yang tersedia</p>
            </div>
        </div>

        <!-- Search -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request("search") }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(request("search"))
                        <a href="{{ route("kasir.produk.index") }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-list me-2" style="color: #bc0007;"></i>Daftar Produk</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Insentif</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produk as $item)
                                    <tr>
                                        <td><strong>{{ $loop->iteration }}</strong></td>
                                        <td>
                                            <strong>{{ $item->produk_nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($item->produk_deskripsi ?? "-", 40) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">Rp {{ number_format($item->produk_harga, 0, ",", ".") }}</span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $item->produk_stok > 5 ? "#43e97b" : ($item->produk_stok > 0 ? "#ffc107" : "#f5576c") }};">
                                                {{ $item->produk_stok }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Rp {{ number_format($item->produk_insentif ?? 0, 0, ",", ".") }}</span>
                                        </td>
                                        <td>
                                            @if($item->created_at)
                                                {{ \Carbon\Carbon::parse($item->created_at)->locale("id")->isoFormat("D MMM YYYY") }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-muted">Tidak ada produk ditemukan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($produk->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    {{ $produk->links() }}
                </div>
            </div>
        @endif
    </div>
</x-supvis.SupvisLayouts>

