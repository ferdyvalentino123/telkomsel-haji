<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Konfirmasi Injeksi — {{ $transaksi->id_transaksi }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7f9;
            color: #1a1a1a;
            font-size: 13px;
        }

        .page-wrapper {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .letter-header {
            background: linear-gradient(135deg, #bc0007 0%, #e2241d 100%);
            padding: 28px 36px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .letter-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l2.5 12.5L45 15l-12.5 2.5L30 30l-2.5-12.5L15 15l12.5-2.5L30 0z' fill='%23fff' fill-opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
        }
        .header-brand { position: relative; }
        .brand-name { font-size: 1.4rem; font-weight: 800; letter-spacing: -0.5px; }
        .brand-sub { font-size: 0.78rem; opacity: 0.8; margin-top: 2px; }

        .header-badge {
            position: relative;
            background: rgba(255,255,255,0.15);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            padding: 8px 16px;
            text-align: center;
            backdrop-filter: blur(4px);
        }
        .header-badge .badge-label { font-size: 0.68rem; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-badge .badge-val { font-size: 1rem; font-weight: 800; }

        /* ===== TITLE SECTION ===== */
        .title-section {
            background: #fafbfc;
            border-bottom: 2px solid #eaeaea;
            padding: 20px 36px;
            text-align: center;
        }
        .surat-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .surat-no {
            font-size: 0.82rem;
            color: #888;
            margin-top: 4px;
        }

        /* ===== BODY ===== */
        .letter-body {
            padding: 28px 36px;
        }

        /* Status Banner */
        .status-banner {
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .status-icon {
            width: 48px; height: 48px;
            background: #10b981;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .status-icon i { color: #fff; font-size: 1.3rem; }
        .status-text .title { font-size: 1rem; font-weight: 700; color: #065f46; }
        .status-text .sub { font-size: 0.82rem; color: #047857; margin-top: 2px; }

        /* Info Grid */
        .info-section { margin-bottom: 24px; }
        .section-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #bc0007;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #fde8e8;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .info-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #eaeaea;
        }
        .info-item .lbl { font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
        .info-item .val { font-size: 0.9rem; font-weight: 700; color: #111; }

        /* MSISDN Table */
        .msisdn-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 0.85rem;
        }
        .msisdn-table th {
            background: #bc0007;
            color: #fff;
            padding: 8px 14px;
            text-align: left;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .msisdn-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #eaeaea;
        }
        .msisdn-table tr:last-child td { border-bottom: none; }
        .msisdn-table tr:nth-child(even) td { background: #fafbfc; }
        .msisdn-table .status-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: #e8f5e9; color: #1a7a4a; border: 1px solid #a5d6a7;
            border-radius: 6px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600;
        }

        /* Proof Section */
        .proof-section {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1.5px solid #93c5fd;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .proof-section .proof-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1d4ed8;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .proof-link {
            display: inline-flex; align-items: center; gap: 8px;
            background: #1d4ed8; color: #fff;
            border-radius: 8px; padding: 8px 16px;
            font-weight: 600; font-size: 0.85rem;
            text-decoration: none;
        }
        .proof-link:hover { background: #1e40af; }

        /* Total */
        .total-box {
            background: linear-gradient(135deg, rgba(188,0,7,0.05), rgba(236,29,36,0.05));
            border: 2px solid #bc0007;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .total-box .lbl { font-size: 0.85rem; color: #666; font-weight: 500; }
        .total-box .val { font-size: 1.4rem; font-weight: 800; color: #bc0007; }

        /* Admin Sign */
        .admin-sign {
            background: #fafbfc;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .admin-sign .sign-title { font-size: 0.72rem; text-transform: uppercase; color: #888; letter-spacing: 0.5px; margin-bottom: 6px; }
        .admin-sign .sign-name { font-size: 1rem; font-weight: 700; color: #111; }
        .admin-sign .sign-role { font-size: 0.8rem; color: #666; }

        /* Footer */
        .letter-footer {
            background: #fafbfc;
            border-top: 1px solid #eaeaea;
            padding: 16px 36px;
            text-align: center;
            font-size: 0.75rem;
            color: #888;
        }
        .letter-footer strong { color: #bc0007; }

        /* Print Button */
        .print-actions {
            max-width: 800px;
            margin: 2rem auto 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 0;
        }
        .btn-print {
            display: inline-flex; align-items: center; gap: 8px;
            background: #bc0007; color: #fff;
            padding: 10px 22px; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem;
            border: none; cursor: pointer; transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(188,0,7,0.2);
        }
        .btn-print:hover { background: #8a0005; color: #fff; transform: translateY(-1px); }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #333;
            padding: 10px 22px; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem;
            border: 1px solid #ddd; cursor: pointer; transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-back:hover { background: #f8f9fa; border-color: #ccc; }

        @media print {
            body { background: #fff; }
            .page-wrapper { margin: 0; border-radius: 0; box-shadow: none; }
            .print-actions { display: none; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    {{-- Tombol aksi (hilang saat print) --}}
    <div class="print-actions">
        <a href="{{ route('travel.transaksi.show', $transaksi->id) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="page-wrapper">

        {{-- Header --}}
        <div class="letter-header">
            <div class="header-brand">
                <div class="brand-name">TELKOMSEL PONDOK HAJI</div>
                <div class="brand-sub">Program Haji & Umrah — Sistem Manajemen Travel</div>
            </div>
            <div class="header-badge">
                <div class="badge-label">No. Referensi</div>
                <div class="badge-val" style="font-size:0.85rem;font-family:monospace;">{{ $transaksi->id_transaksi }}</div>
            </div>
        </div>

        {{-- Title --}}
        <div class="title-section">
            <div class="surat-title">Surat Konfirmasi Injeksi Paket</div>
            <div class="surat-no">Dokumen ini menjadi bukti resmi bahwa paket telah berhasil diinjeksi ke nomor jamaah</div>
        </div>

        <div class="letter-body">

            {{-- Status Banner --}}
            <div class="status-banner">
                <div class="status-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="status-text">
                    <div class="title">Paket Berhasil Diinjeksi</div>
                    <div class="sub">
                        Seluruh {{ $grupTransaksi->count() }} nomor MSISDN telah menerima paket
                        <strong>{{ $transaksi->produk->produk_nama ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            {{-- Info Paket --}}
            <div class="info-section">
                <div class="section-title"><i class="fas fa-suitcase-rolling me-1"></i> Informasi Paket</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="lbl">Nama Paket</div>
                        <div class="val">{{ $transaksi->produk->produk_nama ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Harga Per Paket</div>
                        <div class="val">Rp {{ number_format($transaksi->produk->produk_harga_akhir ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Jumlah Jamaah</div>
                        <div class="val">{{ $grupTransaksi->count() }} Orang</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Total Pembayaran</div>
                        <div class="val" style="color:#bc0007;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Travel</div>
                        <div class="val">{{ $transaksi->supervisor->name ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Tanggal Transaksi</div>
                        <div class="val">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- Info Injeksi --}}
            <div class="info-section">
                <div class="section-title"><i class="fas fa-user-shield me-1"></i> Detail Proses Injeksi</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="lbl">Diinjeksi oleh</div>
                        <div class="val">{{ $firstActivated->injeksi_oleh ?? 'Admin' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Waktu Injeksi</div>
                        <div class="val">{{ $firstActivated->injeksi_at ? \Carbon\Carbon::parse($firstActivated->injeksi_at)->format('d M Y H:i') : '-' }}</div>
                    </div>
                    @if($firstActivated->catatan_injeksi)
                        <div class="info-item" style="grid-column: span 2;">
                            <div class="lbl">Catatan Injeksi</div>
                            <div class="val">{{ $firstActivated->catatan_injeksi }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Daftar MSISDN --}}
            <div class="info-section">
                <div class="section-title"><i class="fas fa-sim-card me-1"></i> Daftar Nomor MSISDN Jamaah</div>
                <table class="msisdn-table">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Nomor MSISDN</th>
                            <th>ID Transaksi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupTransaksi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong style="font-family:monospace;">{{ $item->nomor_telepon }}</strong></td>
                                <td style="font-family:monospace;font-size:0.78rem;color:#666;">{{ $item->id_transaksi }}</td>
                                <td>
                                    <span class="status-chip">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bukti Injeksi --}}
            <div class="proof-section">
                <div class="proof-label"><i class="fas fa-image me-1"></i> Bukti Injeksi Resmi</div>
                <p style="font-size:0.82rem; color:#1d4ed8; margin-bottom:10px;">
                    Screenshot injeksi dari sistem Telkomsel telah disimpan. Klik tombol di bawah untuk membuka file bukti.
                </p>
                <a href="{{ asset('storage/' . $firstActivated->bukti_injeksi) }}" target="_blank" class="proof-link">
                    <i class="fas fa-external-link-alt"></i> Buka Bukti Injeksi
                </a>
            </div>

            {{-- Admin Signature Box --}}
            <div class="admin-sign">
                <div class="sign-title">Diproses dan diverifikasi oleh:</div>
                <div class="sign-name"><i class="fas fa-user-shield me-2" style="color:#bc0007;"></i>{{ $firstActivated->injeksi_oleh ?? 'Admin Telkomsel' }}</div>
                <div class="sign-role">Administrator Sistem — Telkomsel RoaMAX Haji</div>
                <div style="margin-top:6px;font-size:0.78rem;color:#888;">
                    <i class="fas fa-clock me-1"></i>
                    {{ $firstActivated->injeksi_at ? \Carbon\Carbon::parse($firstActivated->injeksi_at)->format('d M Y \p\u\k\u\l H:i \W\I\B') : '-' }}
                </div>
            </div>

            {{-- Disclaimer --}}
            <div style="background:#fff8f8;border:1px solid #fde8e8;border-radius:8px;padding:12px 16px;font-size:0.78rem;color:#7f1d1d;line-height:1.6;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Catatan:</strong> Dokumen ini adalah konfirmasi resmi bahwa paket Telkomsel telah diinjeksi secara manual ke nomor MSISDN jamaah yang tertera. Proses injeksi dilakukan oleh administrator menggunakan sistem internal Telkomsel. Simpan dokumen ini sebagai bukti aktivasi.
            </div>

        </div>

        {{-- Footer --}}
        <div class="letter-footer">
            <strong>S I S T E R</strong> — Sistem Telkomsel RoaMAX Haji<br>
            Dokumen ini dibuat otomatis oleh sistem pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
        </div>

    </div>

</body>
</html>
