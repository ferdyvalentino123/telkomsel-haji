<x-Kasir.KasirLayouts><main class="content"><div class="container-fluid p-0">
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title mb-0"><i class="fas fa-history"></i> Pantau Stok</h1>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i></button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>ID</th><th>Produk</th><th>Tipe</th><th>Qty</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                    @forelse($stockHistory as $item)
                        <tr>
                            <td>#{{ $item->id }}</td>
                            <td>
                                @if($item->product)
                                    {{ $item->product->produk_nama }}
                                @elseif($item->merchandise)
                                    {{ $item->merchandise->merch_nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $item->action }}</span></td>
                            <td>{{ $item->change_amount }}</td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5">Tidak ada riwayat stok</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></main></x-Kasir.KasirLayouts>


