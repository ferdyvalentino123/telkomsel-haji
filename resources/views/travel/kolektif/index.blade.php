@extends("travel.layout")
@section("title", "Pembelian Kolektif")

@section("css")
<style>
    :root { --primary: #bc0007; --primary-dark: #8a0005; --primary-light: #ec1d24; }
    .card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
    .card-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 1.5rem; border: none; }
    .form-label { font-weight: 600; color: #444; }
    .btn-tsel { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; border: none; padding: 0.8rem 2rem; border-radius: 10px; font-weight: 700; transition: all 0.3s; }
    .btn-tsel:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(188, 0, 7, 0.3); color: white; }
    .instruction-card { background-color: rgba(188, 0, 7, 0.05); border-left: 5px solid var(--primary); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
    .msisdn-input.is-invalid { border-color: #dc3545 !important; }
    .msisdn-input.is-valid { border-color: #28a745 !important; }
    .msisdn-feedback { font-size: 0.8rem; margin-top: 4px; display: none; }
    .msisdn-feedback.text-danger { display: block; }
    .msisdn-feedback.text-success { display: block; }
    
    /* Product Selector Styling */
    .product-card { transition: all 0.3s ease; border-color: #dee2e6; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; border-color: #adb5bd !important; }
    .product-radio:checked + .product-card { border-color: var(--primary) !important; border-width: 2px !important; box-shadow: 0 8px 20px rgba(188, 0, 7, 0.15) !important; background-color: rgba(188, 0, 7, 0.02); }
    .product-radio:checked + .product-card .check-indicator { opacity: 1 !important; transform: scale(1) !important; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .product-radio:checked + .product-card .product-icon { background-color: var(--primary) !important; }
    .product-radio:checked + .product-card .product-icon i { color: white !important; }
</style>
@endsection

@section("content")
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-white font-weight-bold"><i class="fas fa-users mr-2"></i> Pembelian Kolektif Paket</h3>
                    <p class="mb-0 opacity-80">Daftarkan banyak nomor sekaligus dalam satu transaksi</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i> {!! session('error') !!}
                        </div>
                    @endif

                    <form action="{{ route('travel.kolektif.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Pilih Paket Perjalanan <span class="text-danger">*</span></label>
                            
                            @if($produks->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada paket perjalanan yang tersedia saat ini.
                                </div>
                            @else
                                <div class="row mt-2">
                                    @foreach($produks as $produk)
                                        <div class="col-md-6 col-lg-6 mb-3">
                                            <label class="product-selector w-100 h-100 m-0" style="cursor: pointer;">
                                                <input type="radio" name="produk_id" value="{{ $produk->id }}" class="d-none product-radio" required {{ old('produk_id') == $produk->id ? 'checked' : '' }}>
                                                <div class="product-card border rounded p-3 h-100 transition-all position-relative overflow-hidden">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="product-icon bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                                            <i class="fas fa-mosque" style="color: var(--primary); font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-1 font-weight-bold" style="color: #333;">{{ $produk->produk_nama }}</h5>
                                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Stok: {{ $produk->produk_stok }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px;">
                                                        {{ $produk->produk_detail }}
                                                    </p>
                                                    
                                                    <div class="price-badge bg-light rounded p-2 text-center mt-auto">
                                                        <span class="d-block text-muted small font-weight-bold mb-1">Harga Paket</span>
                                                        <span class="font-weight-bold" style="color: var(--primary); font-size: 1.25rem;">Rp {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}</span>
                                                    </div>
                                                    
                                                    <div class="check-indicator position-absolute" style="top: 15px; right: 15px; opacity: 0; transform: scale(0);">
                                                        <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('produk_id') <div class="text-danger mt-2 small"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Daftar Nomor MSISDN (Nomor HP) <span class="text-danger">*</span></span>
                                <div class="d-flex gap-2" style="gap: 8px;">
                                    <!-- Import Excel -->
                                    <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" id="btn-import-excel" style="border-radius: 6px;" title="Import nomor dari file Excel/CSV">
                                        <i class="fas fa-file-excel mr-1"></i> Import Excel
                                    </button>
                                    <input type="file" id="excel-file-input" accept=".xlsx,.xls,.csv" style="display:none;">
                                    <!-- Tambah Manual -->
                                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" id="btn-add-msisdn" style="border-radius: 6px;">
                                        <i class="fas fa-plus mr-1"></i> Tambah Nomor
                                    </button>
                                </div>
                            </label>
                            
                            <div class="instruction-card mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Masukkan nomor HP Telkomsel jamaah (diawali <strong>0811, 0812, 0813, 0821, 0822, 0823, 0851, 0852, 0853</strong>, dst).
                                    Prefix <strong>62</strong> atau <strong>+62</strong> otomatis dikonversi ke <strong>0</strong>.
                                    Panjang digit: <strong>10–13 digit</strong>. Nomor duplikat tidak diperbolehkan.<br>
                                    <i class="fas fa-download mr-1 text-success"></i>
                                    <a href="#" id="btn-download-template" class="text-success font-weight-bold">Download template Excel</a> — isi kolom pertama dengan nomor HP jamaah.
                                </small>
                            </div>

                            <!-- Import result toast -->
                            <div id="import-toast" style="display:none; margin-bottom: 10px;" class="alert alert-info border-0 shadow-sm py-2">
                                <i class="fas fa-table mr-1"></i>
                                <span id="import-toast-msg"></span>
                            </div>

                            <div id="msisdn-container">
                                @if(old('msisdns') && is_array(old('msisdns')))
                                    @foreach(old('msisdns') as $index => $oldMsisdn)
                                        <div class="msisdn-row mb-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt text-muted"></i></span>
                                                </div>
                                                <input type="text" name="msisdns[]" class="form-control msisdn-input" placeholder="Contoh: 08123456789" value="{{ $oldMsisdn }}" required maxlength="13">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-danger btn-remove-msisdn" {{ $index == 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="msisdn-feedback"></div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="msisdn-row mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-mobile-alt text-muted"></i></span>
                                            </div>
                                            <input type="text" name="msisdns[]" class="form-control msisdn-input" placeholder="Contoh: 08123456789" required maxlength="13">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger btn-remove-msisdn" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="msisdn-feedback"></div>
                                    </div>
                                @endif
                            </div>
                            @error('msisdns') <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</div> @enderror
                            @error('msisdns.*') <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle mr-1"></i> Beberapa nomor tidak valid.</div> @enderror
                        </div>


                        <div class="text-right mt-5">
                            <button type="submit" class="btn btn-tsel btn-block btn-lg">
                                <i class="fas fa-shopping-cart mr-2"></i> Proses Pembelian Kolektif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<!-- SheetJS untuk parsing Excel client-side -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // Prefiks Telkomsel yang valid
    const TSEL_PREFIXES = [
        '0811','0812','0813','0821','0822','0823',
        '0851','0852','0853','0828','0838'
    ];

    function normalizeMsisdn(value) {
        let v = value.trim();
        // Hapus karakter non-digit kecuali +
        v = v.replace(/[^\d+]/g, '');
        // Konversi +62 atau 62 ke 0
        if (v.startsWith('+62')) v = '0' + v.slice(3);
        else if (v.startsWith('62')) v = '0' + v.slice(2);
        return v;
    }

    function isTselPrefix(number) {
        return TSEL_PREFIXES.some(p => number.startsWith(p));
    }

    function getAllMsisdnInputs() {
        return Array.from(document.querySelectorAll('.msisdn-input'));
    }

    function validateInput(input) {
        const raw = input.value;
        const normalized = normalizeMsisdn(raw);
        input.value = normalized; // auto-format

        const feedback = input.closest('.msisdn-row').querySelector('.msisdn-feedback');
        feedback.className = 'msisdn-feedback';
        input.classList.remove('is-invalid', 'is-valid');

        if (!normalized) return true; // empty — let HTML required handle it

        // Cek panjang digit
        if (normalized.length < 10 || normalized.length > 13) {
            input.classList.add('is-invalid');
            feedback.classList.add('text-danger');
            feedback.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Panjang nomor harus 10–13 digit (saat ini ' + normalized.length + ' digit).';
            return false;
        }

        // Cek prefix Telkomsel
        if (!isTselPrefix(normalized)) {
            input.classList.add('is-invalid');
            feedback.classList.add('text-danger');
            feedback.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Bukan nomor Telkomsel. Prefix harus 0811, 0812, 0813, 0821, 0822, 0823, 0851, 0852, 0853, dll.';
            return false;
        }

        // Cek duplikat
        const allInputs = getAllMsisdnInputs();
        const values = allInputs.map(i => normalizeMsisdn(i.value)).filter(v => v.length > 0);
        const duplicates = values.filter(v => v === normalized);
        if (duplicates.length > 1) {
            input.classList.add('is-invalid');
            feedback.classList.add('text-danger');
            feedback.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Nomor ini sudah dimasukkan sebelumnya. Tidak boleh duplikat dalam satu transaksi.';
            return false;
        }

        input.classList.add('is-valid');
        feedback.classList.add('text-success');
        feedback.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Nomor Telkomsel valid.';
        return true;
    }

    function revalidateAll() {
        getAllMsisdnInputs().forEach(input => {
            if (input.value.trim()) validateInput(input);
        });
    }

    function createMsisdnRow(isFirst = false) {
        const row = document.createElement('div');
        row.className = 'msisdn-row mb-2';
        row.innerHTML = `
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-mobile-alt text-muted"></i></span>
                </div>
                <input type="text" name="msisdns[]" class="form-control msisdn-input" placeholder="Contoh: 08123456789" required maxlength="13">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-danger btn-remove-msisdn" ${isFirst ? 'disabled' : ''}>
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="msisdn-feedback"></div>
        `;
        return row;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('msisdn-container');
        const btnAdd = document.getElementById('btn-add-msisdn');
        const form = container.closest('form');

        // Delegasi event: validasi input saat user mengetik
        container.addEventListener('input', function(e) {
            if (e.target.classList.contains('msisdn-input')) {
                // Sedikit delay agar user selesai mengetik
                clearTimeout(e.target._debounce);
                e.target._debounce = setTimeout(() => {
                    validateInput(e.target);
                    revalidateAll(); // cek duplikat di semua row
                }, 300);
            }
        });

        container.addEventListener('blur', function(e) {
            if (e.target.classList.contains('msisdn-input')) {
                validateInput(e.target);
                revalidateAll();
            }
        }, true);

        // Tambah nomor
        btnAdd.addEventListener('click', function() {
            const row = createMsisdnRow(false);
            container.appendChild(row);
            row.querySelector('.msisdn-input').focus();
        });

        // Hapus nomor
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-msisdn');
            if (btn && !btn.hasAttribute('disabled')) {
                btn.closest('.msisdn-row').remove();
                revalidateAll();
            }
        });

        // Validasi semua sebelum submit
        form.addEventListener('submit', function(e) {
            const inputs = getAllMsisdnInputs();
            let hasError = false;
            inputs.forEach(input => {
                if (!validateInput(input)) hasError = true;
            });
            if (hasError) {
                e.preventDefault();
                // Scroll ke error pertama
                const firstInvalid = container.querySelector('.msisdn-input.is-invalid');
                if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Trigger validasi awal jika ada old values
        getAllMsisdnInputs().forEach(input => {
            if (input.value.trim()) {
                const normalized = normalizeMsisdn(input.value);
                input.value = normalized;
            }
        });

        // ─── Import Excel ────────────────────────────────────────────────
        const btnImport   = document.getElementById('btn-import-excel');
        const fileInput   = document.getElementById('excel-file-input');
        const importToast = document.getElementById('import-toast');
        const importMsg   = document.getElementById('import-toast-msg');

        btnImport.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Reset input agar file yang sama bisa dipilih lagi
            const fileName = file.name;
            const reader = new FileReader();

            reader.onload = function (e) {
                try {
                    const data  = new Uint8Array(e.target.result);
                    const wb    = XLSX.read(data, { type: 'array' });

                    // Prioritas sheet: "Data Nomor" > sheet terakhir > sheet pertama
                    let sheetName = wb.SheetNames[0];
                    if (wb.SheetNames.includes('Data Nomor')) {
                        sheetName = 'Data Nomor';
                    } else if (wb.SheetNames.length > 1) {
                        sheetName = wb.SheetNames[wb.SheetNames.length - 1];
                    }

                    const ws    = wb.Sheets[sheetName];
                    const rows  = XLSX.utils.sheet_to_json(ws, { header: 1, raw: false });

                    let loaded = 0, skipped = 0;
                    const numbers = [];

                    rows.forEach((row, i) => {
                        if (!row || row.length === 0) return;
                        // Kolom pertama sebagai nomor HP
                        let cell = String(row[0] ?? '').trim();
                        if (!cell) return;

                        // Lewati baris header (teks tidak dimulai digit atau +)
                        if (!/^[+\d]/.test(cell)) { skipped++; return; }

                        const normalized = normalizeMsisdn(cell);
                        if (!normalized) { skipped++; return; }

                        numbers.push(normalized);
                    });

                    if (numbers.length === 0) {
                        showToast('danger', `Tidak ada nomor ditemukan di file <strong>${fileName}</strong>. Pastikan kolom pertama berisi nomor HP.`);
                        return;
                    }

                    // Kosongkan container lalu isi dari import
                    container.innerHTML = '';
                    numbers.forEach((num, idx) => {
                        const row = createMsisdnRow(idx === 0);
                        container.appendChild(row);
                        const inp = row.querySelector('.msisdn-input');
                        inp.value = num;
                        validateInput(inp);
                        if (inp.classList.contains('is-valid')) loaded++;
                        else skipped++;
                    });

                    // Pastikan ada minimal 1 row jika semua gagal
                    if (container.querySelectorAll('.msisdn-row').length === 0) {
                        container.appendChild(createMsisdnRow(true));
                    }

                    // Update tombol hapus row pertama
                    const firstRemoveBtn = container.querySelector('.btn-remove-msisdn');
                    if (firstRemoveBtn) firstRemoveBtn.setAttribute('disabled', '');

                    revalidateAll();

                    showToast('info',
                        `<strong>${fileName}</strong> berhasil dibaca. ` +
                        `<span class="text-success font-weight-bold">${loaded} nomor valid</span>` +
                        (skipped > 0 ? `, <span class="text-danger">${skipped} dilewati</span> (header/tidak valid)` : '') +
                        '.');

                } catch (err) {
                    showToast('danger', 'Gagal membaca file Excel: ' + err.message);
                }
            };

            reader.readAsArrayBuffer(file);
            fileInput.value = ''; // reset agar bisa pilih file sama lagi
        });

        function showToast(type, html) {
            importToast.className = `alert alert-${type} border-0 shadow-sm py-2`;
            importMsg.innerHTML   = html;
            importToast.style.display = 'block';
            // Auto-hide setelah 8 detik
            clearTimeout(importToast._timer);
            importToast._timer = setTimeout(() => importToast.style.display = 'none', 8000);
        }

        // ─── Download Template Excel ─────────────────────────────────────
        document.getElementById('btn-download-template').addEventListener('click', function (e) {
            e.preventDefault();

            const wb = XLSX.utils.book_new();

            // ── Sheet 1: Panduan ──────────────────────────────────────────
            const panduanData = [
                ['PANDUAN IMPORT NOMOR JAMAAH - TELKOMSEL HAJI'],
                [''],
                ['CARA PENGGUNAAN:'],
                ['1. Buka sheet "Data Nomor" di file ini'],
                ['2. Copy kolom nomor HP jamaah dari data internal Anda'],
                ['3. Paste ke kolom A mulai baris 5 (setelah 3 baris contoh)'],
                ['4. Hapus baris contoh (baris 2-4) sebelum import'],
                ['5. Simpan file lalu klik "Import Excel" di halaman pembelian kolektif'],
                [''],
                ['FORMAT NOMOR YANG DITERIMA:'],
                ['Format', 'Contoh', 'Hasil setelah konversi'],
                ['Awalan 0 (lokal)', '08123456789', '08123456789'],
                ['Awalan 62 (tanpa +)', '628123456789', '08123456789'],
                ['Awalan +62 (internasional)', '+628123456789', '08123456789'],
                [''],
                ['KETENTUAN:'],
                ['- Hanya nomor Telkomsel yang diterima'],
                ['- Prefix valid: 0811, 0812, 0813, 0821, 0822, 0823, 0851, 0852, 0853, 0828, 0838'],
                ['- Panjang nomor: 10 hingga 13 digit'],
                ['- Nomor duplikat dalam satu transaksi tidak diperbolehkan'],
                ['- Kolom B (Nama Jamaah) bersifat opsional, tidak diproses sistem'],
            ];
            const wsPanduan = XLSX.utils.aoa_to_sheet(panduanData);
            wsPanduan['!cols'] = [{ wch: 38 }, { wch: 20 }, { wch: 25 }];
            XLSX.utils.book_append_sheet(wb, wsPanduan, 'Panduan');

            // ── Sheet 2: Data Nomor (sheet kerja) ─────────────────────────
            const dataNomor = [
                ['Nomor HP Jamaah *', 'Nama Jamaah (opsional)'],   // baris 1: header
                ['08123456789',   '← CONTOH, hapus sebelum import'],  // baris 2
                ['081234567890',  '← CONTOH, hapus sebelum import'],  // baris 3
                ['081234567891',  '← CONTOH, hapus sebelum import'],  // baris 4
                // baris 5+: paste nomor jamaah di sini ↓
            ];
            const wsData = XLSX.utils.aoa_to_sheet(dataNomor);
            wsData['!cols'] = [{ wch: 25 }, { wch: 35 }];
            XLSX.utils.book_append_sheet(wb, wsData, 'Data Nomor');

            XLSX.writeFile(wb, 'template_nomor_jamaah_kolektif.xlsx');
        });
    });
</script>
@endsection

