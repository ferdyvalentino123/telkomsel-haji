<x-supvis.SupvisLayouts>
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title mb-0"><i class="fas fa-gift"></i> Daftar Merchandise</h1>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari merchandise..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>No</th><th>Nama</th><th>Stok</th><th>Dibuat</th></tr>
                </thead>
                <tbody>
                    @forelse($merchandise as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->merch_nama }}</td>
                            <td><span class="badge bg-info">{{ $item->merch_stok }}</span></td>
                            <td>{{ $item->created_at?->format('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5">Tidak ada merchandise</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-supvis.SupvisLayouts>

