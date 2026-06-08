<x-Supvis.SupvisLayouts>
    <style>
        .custom-bg {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
        }
        .custom-text-success {
            color: rgb(33, 226, 62);
        }
        .custom-text-danger {
            color: #FF4D4D;
        }
        .custom-card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .custom-button {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
            border: none;
            padding: 8px 16px; 
            font-size: 1rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .custom-button:hover {
            background: linear-gradient(135deg, #2575FC, rgb(33, 226, 62));
        }
    </style>

    <div class="container mt-5">
        <h1 class="text-center mb-4"><strong>Detail Produk</strong></h1>

        <div class="card custom-card">
            <div class="card-header text-center custom-bg">
                <h2 class="mb-0">{{ $produk->produk_nama }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="h5"><strong>Harga:</strong> <span class="custom-text-success">Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}</span></p>
                        <p class="h5"><strong>Diskon:</strong> <span class="custom-text-danger">{{ $produk->produk_diskon }}%</span></p>
                        <p class="h5"><strong>Stok:</strong> <span class="text-info">{{ $produk->produk_stok }}</span></p>
                        <p class="h5"><strong>Status:</strong> <span class="{{ $produk->is_active ? 'custom-text-success' : 'custom-text-danger' }}">{{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5"><strong>Detail:</strong> {{ $produk->produk_detail ?? 'Tidak ada detail' }}</p>
                        <p class="h5"><strong>Insentif:</strong> <span class="custom-text-success">Rp {{ number_format($produk->produk_insentif, 0, ',', '.') ?? 'Tidak ada insentif' }}</span></p>
                        <p class="h5"><strong>Merchandise:</strong> 
                            <span>
                                @if ($produk->merchandises->isEmpty())
                                    Tidak ada merchandise terkait.
                                @else
                                    {{ $produk->merchandises->pluck('merch_nama')->join(', ') }}
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('produk.index') }}" class="btn custom-button">Kembali ke Daftar Produk</a>
        </div>
    </div>
</x-Supvis.SupvisLayouts>
