<x-kasir.layouts><main class="content"><div class="container-fluid p-0">

    <head>
        <style>
            body {
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 1000px;
                margin: 30px auto;
                background: none;
                border-radius: 0;
                overflow: visible;
                box-shadow: none;
            }

            .title {
                text-align: center;
                margin: 25px 0;
                font-size: 23px;
                font-weight: bold;
                color: #333;
            }

            form {
                padding: 30px;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-group label {
                display: block;
                font-size: 14px;
                margin-bottom: 10px;
                color: #333;
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 12px;
                font-size: 13px;
                border: 1px solid #ccc;
                border-radius: 6px;
                box-sizing: border-box;
            }

            .checkbox-group {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 15px;
                padding: 10px;
            }

            .checkbox-box {
                display: flex;
                align-items: center;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 8px;
                background-color: #fefefe;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                cursor: pointer;
                transition: 0.3s;
            }

            .checkbox-box:hover {
                border-color: #2575FC;
                background: linear-gradient(45deg, #2575FC, #00C853);
                box-shadow: 0 4px 8px rgba(0, 86, 179, 0.2);
            }

            .checkbox-box input {
                display: none;
            }

            .checkbox-box label {
                display: flex;
                align-items: center;
                font-size: 8px;
                font-weight: bold;
                cursor: pointer;
                color: #333;
                flex-grow: 1;
            }

            .checkbox-icon {
                display: inline-block;
                width: 20px;
                height: 20px;
                margin-right: 10px;
                border: 2px solid #ccc;
                border-radius: 4px;
                background-color: #fff;
                position: relative;
                transition: background-color 0.3s, border-color 0.3s, transform 0.3s;
            }

            input:checked+label .checkbox-icon {
                background: linear-gradient(45deg, #2575FC, #00C853);
                border-color: #2575FC;
                transform: scale(1.1);
            }

            input:checked+label .checkbox-icon::after {
                content: '✔';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #fff;
                font-size: 10px;
                font-weight: bold;
            }
            
            .custom-checkbox {
                display: flex;
                align-items: center;
                gap: 8px;
            }
        
            .custom-checkbox input[type="checkbox"] {
                appearance: none;
                -webkit-appearance: none;
                width: 20px;
                height: 20px;
                border: 2px solid #007bff;
                border-radius: 4px;
                position: relative;
                cursor: pointer;
                outline: none;
                transition: all 0.2s;
            }
        
            .custom-checkbox input[type="checkbox"]:checked {
                background-color: #007bff;
            }
        
            .custom-checkbox input[type="checkbox"]::after {
                content: '';
                position: absolute;
                left: 5px;
                top: 2px;
                width: 5px;
                height: 10px;
                border: solid white;
                border-width: 0 2px 2px 0;
                opacity: 0;
                transform: rotate(45deg);
                transition: opacity 0.2s ease-in-out;
            }
        
            .custom-checkbox input[type="checkbox"]:checked::after {
                opacity: 1;
            }
        
            .custom-checkbox label {
                margin: 0;
                font-weight: 500;
                cursor: pointer;
            }


            button[type="submit"],
            button[type="button"] {
                padding: 10px 20px;
                font-size: 16px;
                border: none;
                color: white;
                border-radius: 5px;
                cursor: pointer;
            }

            button[type="submit"] {
                background-color: #2575FC;
            }

            button[type="submit"]:hover {
                background-color: #0056b3;
            }

            button[type="button"] {
                background-color: #00C853;
            }

            button[type="button"]:hover {
                background-color: #009624;
            }

            .checkbox-box.highlight {
                background-color: #f0f9ff;
                border: 2px solid #00C853;
            }

            @media (max-width: 600px) {
                .card {
                    max-width: 100%;
                }
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <div class="container">
            <h2>Edit Transaksi</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="editTransaksiForm" action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" class="form-control"
                        value="{{ old('nomor_telepon', $transaksi->nomor_telepon) }}" required>
                </div>

                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control"
                        value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}" required>
                </div>

                <div class="form-group">
                    <label>Produk</label>
                    <select name="produk" class="form-control" required onchange="filterMerchandises(this.value)">
                        @foreach ($produks as $produk)
                            <option value="{{ $produk->id }}"
                                {{ $produk->id == $transaksi->jenis_paket ? 'selected' : '' }}>
                                {{ $produk->produk_nama }} - Rp{{ number_format($produk->produk_harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <div class="form-group">
                    <input type="hidden" name="tanggal_transaksi" class="form-control"
                        value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi) }}" required>
                </div>

                <div class="form-group">
                    <label>Aktivasi Tanggal</label>
                    <input type="date" name="aktivasi_tanggal" class="form-control"
                        value="{{ old('aktivasi_tanggal', $transaksi->aktivasi_tanggal) }}" required>
                </div>

                <div class="form-group">
                    <label>Telepon Pelanggan</label>
                    <input type="text" name="telepon_pelanggan" class="form-control"
                        value="{{ old('telepon_pelanggan', $transaksi->telepon_pelanggan) }}" required>
                </div>

                <div class="form-group">
                    <label>Nomor Injeksi</label>
                    <input type="text" name="nomor_injeksi" class="form-control"
                        value="{{ old('nomor_injeksi', $transaksi->nomor_injeksi ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Pilih Merchandise:</label>
                    <div class="checkbox-group" id="merchandise-container">
                        @foreach ($merchandises as $merchandise)
                            <div class="checkbox-box" data-produk-ids="{{ json_encode($merchandise->produk_ids) }}">
                                <input type="radio" id="merch{{ $merchandise->id }}" name="merchandise"
                                    value="{{ $merchandise->id }}"
                                    {{ $transaksi->merchandise == $merchandise->merch_nama ? 'checked' : '' }}>
                                <label for="merch{{ $merchandise->id }}">
                                    <span class="checkbox-icon"></span>
                                    {{ $merchandise->merch_nama }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Pilih Paket Internet:</label>
                    <div class="checkbox-group">
                        @foreach ($produks as $produk)
                            <div class="checkbox-box">
                                <input type="radio" id="produk{{ $produk->id }}" name="produk"
                                    value="{{ $produk->id }}"
                                    {{ $produk->id == $transaksi->jenis_paket ? 'checked' : '' }}
                                    onchange="filterMerchandises({{ $produk->id }})">
                                <label for="produk{{ $produk->id }}">
                                    <span class="checkbox-icon"></span>
                                    {{ $produk->produk_nama }} <br>
                                    {{ $produk->produk_detail }} <br>
                                    Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="metode_pembayaran" id="metode_pembayaran"
                    value="{{ old('metode_pembayaran', $transaksi->metode_pembayaran) }}">
                
                {{-- Pilihan metode: Tunai atau Non Tunai --}}
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <div class="checkbox-group">
                        <div class="checkbox-box">
                            <input type="radio" id="metode1" name="metode_type" value="Tunai"
                                {{ $transaksi->metode_pembayaran === 'Tunai' ? 'checked' : '' }}>
                            <label for="metode1">
                                <span class="checkbox-icon"></span>
                                Tunai
                            </label>
                        </div>
                        <div class="checkbox-box">
                            <input type="radio" id="metode2" name="metode_type" value="Non Tunai"
                                {{ in_array($transaksi->metode_pembayaran, ['BCA', 'Mandiri', 'BNI', 'BSI']) ? 'checked' : '' }}>
                            <label for="metode2">
                                <span class="checkbox-icon"></span>
                                Non Tunai
                            </label>
                        </div>
                    </div>
                </div>
                
                {{-- Select bank, hanya muncul saat pilih Non Tunai --}}
                <div class="form-group" id="bank-options" style="display: none;">
                    <label for="bank_select">Pilih Bank</label>
                    <select id="bank_select" class="form-control">
                        <option value="">-- Pilih Bank --</option>
                        <option value="BCA" {{ $transaksi->metode_pembayaran === 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="Mandiri" {{ $transaksi->metode_pembayaran === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BNI" {{ $transaksi->metode_pembayaran === 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="BSI" {{ $transaksi->metode_pembayaran === 'BSI' ? 'selected' : '' }}>BSI</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Sales</label>
                    <input type="text" name="nama_sales" class="form-control"
                        value="{{ old('nama_sales', $transaksi->nama_sales) }}" required>
                </div>

                <div class="form-group">
                    <label>Addon Perdana</label>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="addon_perdana" name="addon_perdana" value="1"
                            {{ $transaksi->addon_perdana ? 'checked' : '' }}>
                        <label for="addon_perdana">PERDANA BARU</label>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>

        <script>
            
            function filterMerchandises(selectedProdukId) {
                const merchandises = document.querySelectorAll('#merchandise-container .checkbox-box');
                merchandises.forEach(merchandise => {
                    const produkIds = JSON.parse(merchandise.getAttribute('data-produk-ids'));
                    const checkbox = merchandise.querySelector('input');
                    if (produkIds.includes(Number(selectedProdukId))) {
                        checkbox.disabled = false;
                        merchandise.classList.add('highlight');
                    } else {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        merchandise.classList.remove('highlight');
                    }
                });
            }
            
            function toggleNonTunaiOptions() {
                const metodeTunai = document.getElementById('metode1');
                const metodeNonTunai = document.getElementById('metode2');
                const bankOptions = document.getElementById('bank-options');
                const bankSelect = document.getElementById('bank_select');
                const metodeInput = document.getElementById('metode_pembayaran');
        
                if (metodeTunai.checked) {
                    bankOptions.style.display = 'none';
                    metodeInput.value = 'Tunai';
                } else {
                    bankOptions.style.display = 'block';
                    metodeInput.value = bankSelect.value || '';
                }
            }
        
                function updateMetodePembayaranFromSelect() {
                    const bankSelect = document.getElementById('bank_select');
                    const metodeInput = document.getElementById('metode_pembayaran');
                    metodeInput.value = bankSelect.value;
                }
            
                window.addEventListener('DOMContentLoaded', () => {
                    toggleNonTunaiOptions();
                    document.getElementById('bank_select').addEventListener('change', updateMetodePembayaranFromSelect);
                    document.getElementById('metode1').addEventListener('change', toggleNonTunaiOptions);
                    document.getElementById('metode2').addEventListener('change', toggleNonTunaiOptions);
                });
                window.addEventListener('DOMContentLoaded', toggleNonTunaiOptions);
                document.getElementById('metode1').addEventListener('change', toggleNonTunaiOptions);
                document.getElementById('metode2').addEventListener('change', toggleNonTunaiOptions);

            // Panggil filter di awal saat halaman dimuat
            document.addEventListener("DOMContentLoaded", () => {
                const metodeTunai = document.getElementById('metode1');
                const metodeNonTunai = document.getElementById('metode2');
            
                // Filter merchandise saat produk dipilih
                const selectedProduk = document.querySelector("input[name='produk']:checked")?.value;
                if (selectedProduk) {
                    filterMerchandises(selectedProduk);
                }
            
                // Validasi sebelum kirim form
                document.getElementById('editTransaksiForm').addEventListener('submit', function(e) {
                    const selectedProduk = document.querySelector("input[name='produk']:checked");
                    const selectedMerch = document.querySelector("input[name='merchandise']:checked");
    
                    if (!selectedProduk) {
                        e.preventDefault();
                        Swal.fire({
                            title: "Peringatan!",
                            text: "Harap pilih paket produk!",
                            icon: "warning"
                        });
                        return;
                    }
            
                    if (!selectedMerch || selectedMerch.disabled) {
                        e.preventDefault();
                        Swal.fire({
                            title: "Peringatan!",
                            text: "Harap pilih merchandise yang tersedia!",
                            icon: "warning"
                        });
                        return;
                    }
                    
                    if (metodeNonTunai.checked && !bankSelect.value) {
                        e.preventDefault();
                        Swal.fire({
                            title: "Peringatan!",
                            text: "Harap pilih bank untuk pembayaran non tunai!",
                            icon: "warning"
                        });
                    }
                });
            });
        </script>
    </body>
</div></main></x-kasir.layouts>


