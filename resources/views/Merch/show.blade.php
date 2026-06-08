<x-Supvis.SupvisLayouts>
    <style>
        .custom-bg {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .custom-text-success {
            color: rgb(33, 226, 62);
            font-weight: bold;
        }
        .custom-text-danger {
            color: #FF4D4D;
            font-weight: bold;
        }
        .custom-card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }
        .custom-button {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .custom-button:hover {
            background: linear-gradient(135deg, #2575FC, rgb(33, 226, 62));
            transform: scale(1.08);
        }
        .custom-table {
            background-color: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>

    <div class="container mt-5">
        <h1 class="text-center mb-4"><strong>Detail Merchandise</strong></h1>

        <div class="card custom-card">
            <div class="card-header text-center custom-bg">
                <h2 class="mb-0">{{ $merchandise->merch_nama }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="h5"><strong>Detail:</strong> {{ $merchandise->merch_detail ?? 'Tidak ada detail' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5"><strong>Stok:</strong> <span class="text-info">{{ $merchandise->merch_stok }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('merch.index') }}" class="btn custom-button">Kembali</a>
        </div>
    </div>
</x-Supvis.SupvisLayouts>

