<x-pelanggan.layouts>
    <style>
        :root {
            --tsel-primary: #bc0007;
            --tsel-primary-light: #e2241d;
            --tsel-dark: #1a1c1c;
            --tsel-gray: #f9f9f9;
            --tsel-border: #e2e2e2;
        }

        .page-header {
            background: linear-gradient(135deg, var(--tsel-primary) 0%, var(--tsel-primary-light) 100%);
            border-radius: 16px;
            padding: 24px;
            color: #fff;
            margin-bottom: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(188, 0, 7, 0.15);
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l2.5 12.5L45 15l-12.5 2.5L30 30l-2.5-12.5L15 15l12.5-2.5L30 0zm0 30l2.5 12.5L45 45l-12.5 2.5L30 60l-2.5-12.5L15 45l12.5-2.5L30 30z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }

        .page-header>* {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            margin-bottom: 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .product-info-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--tsel-border);
        }

        .product-showcase {
            text-align: center;
            padding: 24px;
            background: var(--tsel-gray);
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid var(--tsel-border);
        }

        .product-icon-large {
            width: 90px;
            height: 90px;
            background: rgba(188, 0, 7, 0.05);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 3rem;
            color: var(--tsel-primary);
        }

        .product-name-large {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tsel-dark);
            margin-bottom: 10px;
        }

        .product-price-large {
            font-size: 2rem;
            font-weight: 800;
            color: var(--tsel-primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .info-item {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--tsel-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .info-item i {
            font-size: 2rem;
            color: var(--tsel-primary);
            margin-bottom: 5px;
        }

        .info-item-content {
            width: 100%;
        }

        .info-item-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 2px;
        }

        .info-item-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--tsel-dark);
        }

        .purchase-form {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--tsel-border);
        }

        .form-label {
            font-weight: 600;
            color: var(--tsel-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--tsel-primary);
        }

        .form-control {
            border: 1px solid var(--tsel-border);
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--tsel-gray);
        }

        .form-control:focus {
            border-color: var(--tsel-primary);
            box-shadow: 0 0 0 0.2rem rgba(188, 0, 7, 0.1);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-quantity {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            border: 1px solid var(--tsel-border);
            background: #fff;
            color: var(--tsel-dark);
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-quantity:hover {
            background: var(--tsel-gray);
            border-color: var(--tsel-primary);
            color: var(--tsel-primary);
        }

        .quantity-display {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--tsel-dark);
            min-width: 60px;
            text-align: center;
        }

        .total-section {
            background: var(--tsel-gray);
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
            border: 1px solid var(--tsel-border);
        }

        .total-label {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--tsel-primary);
            line-height: 1;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--tsel-primary) 0%, var(--tsel-primary-light) 100%);
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(188, 0, 7, 0.2);
            color: #fff;
        }

        .btn-back {
            background: #fff;
            color: var(--tsel-dark);
            border: 1px solid var(--tsel-border);
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 12px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--tsel-gray);
            color: var(--tsel-dark);
            border-color: #ccc;
        }

        .alert-info-custom {
            background: rgba(188, 0, 7, 0.03);
            color: var(--tsel-dark);
            border: 1px solid rgba(188, 0, 7, 0.1);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 24px;
            font-size: 0.95rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-info-custom i {
            font-size: 1.2rem;
            color: var(--tsel-primary);
            margin-top: 2px;
        }

        /* Target Nomor Styles */
        .target-nomor-options {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .target-option {
            flex: 1;
            position: relative;
        }

        .target-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .target-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 10px;
            border: 2px solid var(--tsel-border);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #fff;
            text-align: center;
            font-size: 0.88rem;
            font-weight: 600;
            color: #666;
        }

        .target-option label i {
            font-size: 1.4rem;
            color: #aaa;
            transition: color 0.25s ease;
        }

        .target-option input[type="radio"]:checked + label {
            border-color: var(--tsel-primary);
            background: rgba(188, 0, 7, 0.04);
            color: var(--tsel-primary);
            box-shadow: 0 0 0 3px rgba(188, 0, 7, 0.08);
        }

        .target-option input[type="radio"]:checked + label i {
            color: var(--tsel-primary);
        }

        .target-option label:hover {
            border-color: var(--tsel-primary);
            color: var(--tsel-primary);
        }

        .target-option label:hover i {
            color: var(--tsel-primary);
        }

        #injeksi-input-wrapper {
            display: none;
            animation: fadeSlideIn 0.3s ease;
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .nomor-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            margin-top: 6px;
        }

        .badge-telkomsel {
            background: rgba(0, 150, 50, 0.1);
            color: #006633;
            border: 1px solid rgba(0, 150, 50, 0.2);
        }

        .badge-error {
            background: rgba(220, 38, 38, 0.08);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .input-nomor-wrapper {
            position: relative;
        }

        .input-nomor-wrapper .input-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 0.95rem;
            font-weight: 600;
            pointer-events: none;
        }

        .input-nomor-wrapper .form-control {
            padding-left: 14px;
        }

        /* Mobile specific styling to make it compact */
        @media (max-width: 768px) {
            .page-header {
                padding: 20px 15px;
                border-radius: 12px;
            }

            .page-header h1 {
                font-size: 1.4rem;
            }

            .page-header p {
                font-size: 0.85rem;
            }

            .product-info-card {
                padding: 15px;
                border-radius: 12px;
            }

            .product-showcase {
                padding: 15px;
                margin-bottom: 15px;
            }

            .product-icon-large {
                width: 60px;
                height: 60px;
                font-size: 2rem;
                margin-bottom: 10px;
            }

            .product-name-large {
                font-size: 1.15rem;
                margin-bottom: 5px;
            }

            .product-price-large {
                font-size: 1.4rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .info-item {
                padding: 12px 15px;
                display: flex;
                flex-direction: row;
                align-items: center;
                text-align: left;
                gap: 15px;
                border-radius: 10px;
            }

            .info-item i {
                font-size: 1.4rem;
                margin-bottom: 0;
            }

            .info-item-content {
                flex: 1;
            }

            .info-item-label {
                margin-bottom: 2px;
                font-size: 0.75rem;
            }

            .info-item-value {
                font-size: 0.95rem;
            }

            .purchase-form {
                padding: 15px;
                border-radius: 12px;
            }

            .alert-info-custom {
                font-size: 0.8rem;
                padding: 12px;
                margin-bottom: 20px;
            }

            .alert-info-custom i {
                font-size: 1rem;
            }

            .form-label {
                font-size: 0.85rem;
                margin-bottom: 5px;
            }

            .form-control {
                padding: 8px 12px;
                font-size: 0.85rem;
            }

            .text-muted {
                font-size: 0.75rem;
            }

            .quantity-controls {
                justify-content: center;
            }

            .total-section {
                padding: 12px;
                margin: 15px 0;
            }

            .total-label {
                font-size: 0.85rem;
            }

            .total-amount {
                font-size: 1.5rem;
            }

            .btn-submit {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .btn-back {
                padding: 8px 20px;
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-shopping-cart"></i> Beli Paket Kuota</h1>
        <p>Lengkapi informasi pembelian di bawah ini</p>
    </div>

    <!-- Product Info -->
    <div class="product-info-card">
        <div class="product-showcase">
            <div class="product-icon-large">
                <i class="fas fa-sim-card"></i>
            </div>
            <h2 class="product-name-large">{{ $produk->produk_nama }}</h2>
            <div class="product-price-large">
                Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-wifi"></i>
                <div class="info-item-content">
                    <div class="info-item-label">Kuota</div>
                    <div class="info-item-value">{{ $produk->kuota ?? 'Utama' }}</div>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-calendar-check"></i>
                <div class="info-item-content">
                    <div class="info-item-label">Masa Aktif</div>
                    <div class="info-item-value">{{ $produk->masa_aktif ?? '30 Hari' }}</div>
                </div>
            </div>
            @if($produk->produk_detail)
            <div class="info-item" style="grid-column: 1 / -1; flex-direction: row; text-align: left;">
                <i class="fas fa-info-circle" style="align-self: flex-start; margin-top: 4px;"></i>
                <div class="info-item-content">
                    <div class="info-item-label">Deskripsi Tambahan</div>
                    <div class="info-item-value" style="font-size: 0.85rem; font-weight: normal; line-height: 1.4;">{{ $produk->produk_detail }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Purchase Form -->
    <div class="purchase-form">
        <div class="alert-info-custom">
            <i class="fas fa-info-circle"></i>
            <strong>Informasi:</strong> Pastikan pembelian sesuai kebutuhan Anda. Setelah transaksi diproses, tim kami
            akan menghubungi Anda untuk konfirmasi paket telah aktif.
        </div>

        <form action="{{ route('pelanggan.transaksi.process') }}" method="POST" id="purchaseForm">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">

            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-user"></i> Nama Pelanggan
                </label>
                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
            </div>

            {{-- Pilihan Target Nomor --}}
            <div class="form-group mb-4">
                <label class="form-label">
                    <i class="fas fa-sim-card"></i> Target Nomor Injeksi
                </label>

                <div class="target-nomor-options">
                    <div class="target-option">
                        <input type="radio" name="target_nomor_type" id="type_sendiri" value="sendiri" checked>
                        <label for="type_sendiri">
                            <i class="fas fa-user-circle"></i>
                            Nomor Pribadi
                        </label>
                    </div>
                    <div class="target-option">
                        <input type="radio" name="target_nomor_type" id="type_injeksi" value="injeksi">
                        <label for="type_injeksi">
                            <i class="fas fa-mobile-alt"></i>
                            Injeksi Nomor Lain
                        </label>
                    </div>
                </div>

                {{-- Nomor Sendiri (readonly dari profil) --}}
                <div id="nomor-sendiri-wrapper">
                    <input type="tel" id="nomor_sendiri_display" class="form-control" 
                        value="{{ Auth::user()->phone }}" readonly>
                    <small class="text-muted"><i class="fas fa-lock" style="font-size:0.75rem;"></i> Nomor diambil otomatis dari profil Anda.</small>
                </div>

                {{-- Input Injeksi Nomor Lain --}}
                <div id="injeksi-input-wrapper">
                    <div class="input-nomor-wrapper">
                        <input type="tel" id="nomor_injeksi_input" class="form-control"
                            placeholder="Contoh: 081234567890"
                            maxlength="13"
                            autocomplete="off">
                    </div>
                    <div id="nomor-status"></div>
                    <small class="text-muted">Masukkan nomor Telkomsel tujuan (10–13 digit).</small>
                </div>

                {{-- Hidden input yang di-submit --}}
                <input type="hidden" name="nomor_telepon" id="nomor_telepon" value="{{ Auth::user()->phone }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="aktivasi_tanggal" class="form-label">
                    <i class="fas fa-calendar-alt"></i> Tanggal Aktivasi Paket
                </label>
                <input type="date" name="aktivasi_tanggal" id="aktivasi_tanggal" class="form-control"
                    min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                <small class="text-muted">Pilih tanggal kapan paket RoaMAX Anda ingin mulai diaktifkan.</small>
            </div>
            {{-- <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-shopping-basket"></i> Jumlah Pembelian
                </label>
                <div class="quantity-controls">
                    <button type="button" class="btn-quantity" onclick="decreaseQuantity()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="quantity-display" id="quantityDisplay">1</div>
                    <button type="button" class="btn-quantity" onclick="increaseQuantity()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <input type="hidden" name="jumlah" id="jumlahInput" value="1">
                <small class="text-muted">Maksimal pembelian: {{ $produk->produk_stok }} paket</small>
            </div> --}}

            <div class="total-section text-center">
                <div class="total-label">Total Pembayaran</div>
                <div class="total-amount" id="totalAmount">
                    Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}
                </div>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-check-circle"></i> Konfirmasi Pembelian
            </button>
            <a href="{{ route('pelanggan.home') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </form>
    </div>

    <script>
        const maxStock = {{ $produk->produk_stok }};
        const basePrice = {{ $produk->produk_harga }};
        let currentQuantity = 1;

        // =============================================
        // MSISDN Telkomsel Prefix Validation
        // =============================================
        // Prefix Telkomsel berdasarkan regulasi Kominfo
        const TELKOMSEL_PREFIXES = [
            '0811','0812','0813','0821','0822','0823',
            '0851','0852','0853',
            '0811','0812','0813',
            '+62811','+62812','+62813','+62821','+62822','+62823',
            '+62851','+62852','+62853',
            '62811','62812','62813','62821','62822','62823',
            '62851','62852','62853'
        ];

        function isTelkomsel(nomor) {
            const cleaned = nomor.replace(/[\s\-().]/g, '');
            return TELKOMSEL_PREFIXES.some(prefix => cleaned.startsWith(prefix));
        }

        function isValidLength(nomor) {
            const cleaned = nomor.replace(/[\s\-().]/g, '');
            // Normalkan: ganti +62 atau 62 di awal menjadi 0 untuk hitung panjang
            let normalized = cleaned;
            if (normalized.startsWith('+62')) normalized = '0' + normalized.slice(3);
            else if (normalized.startsWith('62')) normalized = '0' + normalized.slice(2);
            return normalized.length >= 10 && normalized.length <= 13;
        }

        function validateNomorInjeksi() {
            const input  = document.getElementById('nomor_injeksi_input');
            const status = document.getElementById('nomor-status');
            const hidden = document.getElementById('nomor_telepon');
            const val    = input.value.trim();

            if (val === '') {
                status.innerHTML = '';
                input.classList.remove('is-valid', 'is-invalid');
                hidden.value = '';
                return;
            }

            if (!isValidLength(val)) {
                status.innerHTML = `<span class="nomor-status-badge badge-error"><i class="fas fa-times-circle"></i> Nomor harus 10–13 digit</span>`;
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                hidden.value = '';
                return;
            }

            if (!isTelkomsel(val)) {
                status.innerHTML = `<span class="nomor-status-badge badge-error"><i class="fas fa-exclamation-triangle"></i> Bukan nomor Telkomsel — paket tidak dapat diinjeksi</span>`;
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                hidden.value = '';
                return;
            }

            // Valid Telkomsel
            status.innerHTML = `<span class="nomor-status-badge badge-telkomsel"><i class="fas fa-check-circle"></i> Terdeteksi nomor Telkomsel ✓</span>`;
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            hidden.value = val;
        }

        // Toggle antara nomor sendiri vs injeksi
        function handleTargetChange() {
            const selected = document.querySelector('input[name="target_nomor_type"]:checked').value;
            const sendiriWrapper  = document.getElementById('nomor-sendiri-wrapper');
            const injeksiWrapper  = document.getElementById('injeksi-input-wrapper');
            const hiddenNomor     = document.getElementById('nomor_telepon');
            const injeksiInput    = document.getElementById('nomor_injeksi_input');
            const statusEl        = document.getElementById('nomor-status');

            if (selected === 'sendiri') {
                sendiriWrapper.style.display = 'block';
                injeksiWrapper.style.display = 'none';
                hiddenNomor.value = document.getElementById('nomor_sendiri_display').value;
                injeksiInput.value = '';
                statusEl.innerHTML = '';
                injeksiInput.classList.remove('is-valid', 'is-invalid');
            } else {
                sendiriWrapper.style.display = 'none';
                injeksiWrapper.style.display = 'block';
                hiddenNomor.value = '';
                injeksiInput.focus();
            }
        }

        document.querySelectorAll('input[name="target_nomor_type"]').forEach(radio => {
            radio.addEventListener('change', handleTargetChange);
        });

        document.getElementById('nomor_injeksi_input').addEventListener('input', validateNomorInjeksi);

        // =============================================
        // Quantity controls
        // =============================================
        function updateDisplay() {
            document.getElementById('quantityDisplay').textContent = currentQuantity;
            document.getElementById('jumlahInput').value = currentQuantity;

            const totalPrice = basePrice * currentQuantity;
            document.getElementById('totalAmount').textContent =
                'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        function increaseQuantity() {
            if (currentQuantity < maxStock) {
                currentQuantity++;
                updateDisplay();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: 'Jumlah pembelian tidak boleh melebihi stok yang tersedia',
                    confirmButtonColor: '#bc0007'
                });
            }
        }

        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                updateDisplay();
            }
        }

        // =============================================
        // Form submit handler
        // =============================================
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedType = document.querySelector('input[name="target_nomor_type"]:checked').value;
            const hiddenNomor  = document.getElementById('nomor_telepon').value;

            // Validasi nomor injeksi sebelum lanjut
            if (selectedType === 'injeksi') {
                const injVal = document.getElementById('nomor_injeksi_input').value.trim();

                if (!injVal) {
                    Swal.fire({ icon: 'warning', title: 'Nomor Kosong', text: 'Harap isi nomor tujuan injeksi terlebih dahulu.', confirmButtonColor: '#bc0007' });
                    return;
                }
                if (!isValidLength(injVal)) {
                    Swal.fire({ icon: 'error', title: 'Nomor Tidak Valid', text: 'Nomor harus terdiri dari 10 hingga 13 digit.', confirmButtonColor: '#bc0007' });
                    return;
                }
                if (!isTelkomsel(injVal)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Bukan Nomor Telkomsel',
                        html: `Nomor <strong>${injVal}</strong> tidak terdeteksi sebagai nomor Telkomsel.<br><br>Paket hanya dapat diinjeksi ke nomor Telkomsel (prefix: 0811, 0812, 0813, 0821, 0822, 0823, 0851, 0852, 0853).`,
                        confirmButtonColor: '#bc0007',
                        width: '380px'
                    });
                    return;
                }
            }

            if (!hiddenNomor) {
                Swal.fire({ icon: 'warning', title: 'Nomor Belum Valid', text: 'Pastikan nomor tujuan sudah valid sebelum melanjutkan.', confirmButtonColor: '#bc0007' });
                return;
            }

            const activationDate  = document.getElementById('aktivasi_tanggal').value;
            const formattedDate   = new Date(activationDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const targetLabel     = selectedType === 'sendiri' ? 'Nomor Saya' : 'Injeksi ke Nomor Lain';

            Swal.fire({
                title: '<span style="font-size: 1.2rem; font-weight: 700;">Konfirmasi Pembelian</span>',
                html: `
                    <div style="font-size: 0.85rem; text-align: left; background: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 5px; border: 1px solid #e2e8f0;">
                        <div style="margin-bottom: 6px; color: #64748b;">Paket: <strong style="color: #0f172a; display: block;">{{ $produk->produk_nama }}</strong></div>
                        <div style="margin-bottom: 6px; color: #64748b;">Target: <strong style="color: #0f172a; display: block;">${targetLabel} — ${hiddenNomor}</strong></div>
                        <div style="margin-bottom: 6px; color: #64748b;">Aktivasi: <strong style="color: #0f172a; display: block;">${formattedDate}</strong></div>
                        <div style="color: #64748b;">Total Bayar: <strong style="color: #bc0007; display: block; font-size: 1rem;">Rp ${basePrice.toLocaleString('id-ID')}</strong></div>
                    </div>
                `,
                icon: 'question',
                width: '360px',
                padding: '1.2em',
                showCancelButton: true,
                confirmButtonColor: '#bc0007',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Beli',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>

</x-pelanggan.layouts>

