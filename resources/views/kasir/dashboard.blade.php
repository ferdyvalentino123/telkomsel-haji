<x-kasir.layouts>
<main class="content">
<div class="container-fluid p-0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --tsel-red: #EC1C24;
            --tsel-red-soft: #FFF1F2;
            --tsel-red-gradient: linear-gradient(135deg, #EC1C24 0%, #B91C1C 100%);
            --tsel-dark: #1F2937;
            --tsel-dark-blue: #111827;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.6);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            --card-shadow-hover: 0 20px 40px rgba(236, 28, 36, 0.08);
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dashboard-wrapper {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: radial-gradient(circle at 0% 0%, #fff9f9 0%, #f9fafb 100%);
            min-height: 100vh;
            color: var(--tsel-dark);
            padding: 1.5rem;
        }

        /* Banner Welcome */
        .welcome-banner {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            transition: var(--transition-smooth);
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(236, 28, 36, 0.06) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        .welcome-text {
            position: relative;
            z-index: 1;
        }

        .welcome-text h1 {
            font-size: 2.25rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--tsel-dark-blue) 0%, #4B5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-text p {
            font-size: 1.1rem;
            color: #6B7280;
            margin: 0;
            font-weight: 500;
        }

        .live-clock-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
            position: relative;
            z-index: 1;
        }

        .live-time {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--tsel-red);
            font-variant-numeric: tabular-nums;
        }

        .live-date {
            font-size: 0.9rem;
            font-weight: 600;
            color: #9CA3AF;
            margin-top: 0.25rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.75rem;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: var(--card-shadow);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            border-color: rgba(236, 28, 36, 0.1);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .stat-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: var(--transition-smooth);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--tsel-dark-blue);
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        .stat-footer {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6B7280;
            border-top: 1px dashed #F3F4F6;
            padding-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
        }

        .stat-subtext {
            color: #6B7280;
            font-size: 0.8rem;
            display: flex;
            gap: 12px;
        }

        .subtext-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dot-green { width: 8px; height: 8px; border-radius: 50%; background-color: #10B981; }
        .dot-yellow { width: 8px; height: 8px; border-radius: 50%; background-color: #F59E0B; }

        /* Action Grid */
        .section-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--tsel-dark-blue);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--tsel-red);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }

        .action-card {
            background: white;
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: var(--card-shadow);
            text-decoration: none !important;
            transition: var(--transition-smooth);
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(236, 28, 36, 0.15);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var(--tsel-red-soft);
            color: var(--tsel-red);
            flex-shrink: 0;
            transition: var(--transition-smooth);
        }

        .action-card:hover .action-icon {
            background: var(--tsel-red);
            color: white;
            transform: scale(1.05);
        }

        .action-info {
            display: flex;
            flex-direction: column;
        }

        .action-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--tsel-dark);
            margin-bottom: 0.25rem;
        }

        .action-desc {
            font-size: 0.8rem;
            color: #9CA3AF;
            line-height: 1.3;
        }

        /* Two-Column Grid */
        .two-col-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .dashboard-panel {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        /* Responsive Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 16px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            padding: 1rem 1.25rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #6B7280;
            background: #F9FAFB;
            border-bottom: 2px solid #F3F4F6;
        }

        .custom-table td {
            padding: 1.15rem 1.25rem;
            font-size: 0.9rem;
            color: var(--tsel-dark);
            border-bottom: 1px solid #F3F4F6;
            vertical-align: middle;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover td {
            background: #FAFAFA;
        }

        /* Badges */
        .badge-pill {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success-tsel {
            background-color: #ECFDF5;
            color: #059669;
        }

        .badge-warning-tsel {
            background-color: #FFFBEB;
            color: #D97706;
        }

        .badge-danger-tsel {
            background-color: #FEF2F2;
            color: #DC2626;
        }

        /* Details list */
        .details-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .details-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.85rem;
            border-bottom: 1px dashed #F3F4F6;
        }

        .details-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .details-label {
            font-size: 0.85rem;
            color: #9CA3AF;
            font-weight: 600;
        }

        .details-value {
            font-size: 0.95rem;
            color: var(--tsel-dark-blue);
            font-weight: 700;
        }

        /* Media Queries */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .actions-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .two-col-grid {
                grid-template-columns: 1fr;
            }
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .welcome-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 2rem;
            }
            .live-clock-container {
                align-items: flex-start;
                text-align: left;
                margin-top: 1rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dashboard-wrapper">
        <!-- Glassmorphism Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat Datang, {{ Auth::user()->name ?? 'Kasir' }}!</h1>
                <p>Kelola dan setujui transaksi RoaMAX Haji dengan mudah dan aman.</p>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <!-- Total Transaksi -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Transaksi</span>
                    <div class="stat-icon" style="background: #FFF1F2; color: #EC1C24;">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($totalTransaksi ?? 0) }}</div>
                    <div class="stat-footer">
                        <div class="stat-subtext">
                            <span class="subtext-item"><div class="dot-green"></div> Lunas: {{ $transaksiLunas ?? 0 }}</span>
                            <span class="subtext-item"><div class="dot-yellow"></div> Pending: {{ $transaksiPending ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisa Budget Insentif -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Sisa Budget Insentif</span>
                    <div class="stat-icon" style="background: #ECFDF5; color: #10B981;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div>
                    <div class="stat-value" style="color: #10B981;">Rp {{ number_format($sisaBudget ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-footer">
                        <span style="font-size: 0.8rem; font-weight: 500;">
                            Total Budget: Rp {{ number_format($totalBudget ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Sales</span>
                    <div class="stat-icon" style="background: #EFF6FF; color: #3B82F6;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div>
                    <div class="stat-value" style="color: #3B82F6;">{{ number_format($totalSales ?? 0) }}</div>
                    <div class="stat-footer">
                        <span style="font-size: 0.8rem; font-weight: 500;">Sales terdaftar dalam sistem</span>
                    </div>
                </div>
            </div>

            <!-- Status Kasir -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Status Sistem</span>
                    <div class="stat-icon" style="background: #FFFBEB; color: #F59E0B;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="stat-value" style="color: #F59E0B; font-size: 1.5rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle" style="color: #10B981;"></i> Aktif & Siaga
                    </div>
                    <div class="stat-footer">
                        <span style="font-size: 0.8rem; font-weight: 500;">Login: {{ Auth::user()->role ?? 'Kasir' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Quick Access -->
        <!-- <h2 class="section-title">
            <i class="fas fa-cubes"></i> Akses Cepat Operasional
        </h2>
        <div class="actions-grid">
            <a href="{{ route('kasir.transaksi.approve') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-clipboard-check"></i></div>
                <div class="action-info">
                    <span class="action-name">Approve Transaksi</span>
                    <span class="action-desc">Verifikasi & setujui transaksi masuk secara berkala</span>
                </div>
            </a>

            <a href="{{ route('kasir.transaksi.index') }}" class="action-card">
                <div class="action-icon" style="background: #ECFDF5; color: #10B981;"><i class="fas fa-history"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Riwayat Transaksi</span>
                    <span class="action-desc">Lihat status, detail, dan cetak kwitansi lunas</span>
                </div>
            </a>

            <a href="{{ route('kasir.stock-history.index') }}" class="action-card">
                <div class="action-icon" style="background: #EFF6FF; color: #3B82F6;"><i class="fas fa-boxes"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Pantau Stok</span>
                    <span class="action-desc">Monitor riwayat dan sisa persediaan kartu perdana / merch</span>
                </div>
            </a>

            <a href="{{ route('kasir.budget_insentif.pantau') }}" class="action-card">
                <div class="action-icon" style="background: #FFFBEB; color: #D97706;"><i class="fas fa-chart-line"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Pantau Budget</span>
                    <span class="action-desc">Analisis log pengubahan dan alokasi budget insentif sales</span>
                </div>
            </a>

            <a href="{{ route('kasir.monitor.setoran') }}" class="action-card">
                <div class="action-icon" style="background: #F5F3FF; color: #8B5CF6;"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Monitor Setoran</span>
                    <span class="action-desc">Monitor rekapitulasi setoran dari setiap petugas sales</span>
                </div>
            </a>

            <a href="{{ route('kasir.monitor.void') }}" class="action-card">
                <div class="action-icon" style="background: #FEF2F2; color: #EF4444;"><i class="fas fa-ban"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Void Transaksi</span>
                    <span class="action-desc">Batalkan transaksi atau kembalikan status ke semula</span>
                </div>
            </a>

            <a href="{{ route('kasir.add_sales') }}" class="action-card">
                <div class="action-icon" style="background: #F0FDF4; color: #16A34A;"><i class="fas fa-user-plus"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Tambah Sales</span>
                    <span class="action-desc">Daftarkan akun sales baru ke dalam sistem aplikasi</span>
                </div>
            </a>

            <a href="{{ route('kasir.history-setoran') }}" class="action-card">
                <div class="action-icon" style="background: #FFF7ED; color: #EA580C;"><i class="fas fa-clipboard-list"></i></div>
                <div class="action-info">
                    <span class="action-name" style="color: #1F2937;">Checklist Sales</span>
                    <span class="action-desc">Proses verifikasi setoran masuk untuk sales bersangkutan</span>
                </div>
            </a>
        </div> -->

        <!-- Bottom Grid: Recent Activity -->
        <div class="w-100 mb-4">
            <!-- Recent Transactions Table -->
            <div class="dashboard-panel">
                <h3 class="section-title" style="margin-bottom: 1.25rem;">
                    <i class="fas fa-list-ul"></i> Transaksi Terbaru
                </h3>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Sales</th>
                                <th>Paket / Produk</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr>
                                    <td style="font-weight: 700;">{{ $tx->id_transaksi }}</td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--tsel-dark-blue);">{{ $tx->nama_pelanggan }}</div>
                                        <div style="font-size: 0.75rem; color: #9CA3AF;">{{ $tx->telepon_pelanggan }}</div>
                                    </td>
                                    <td>{{ $tx->nama_sales }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $tx->produk->produk_nama ?? 'Paket Kustom' }}</div>
                                        <div style="font-size: 0.75rem; color: #6B7280;">{{ $tx->metode_pembayaran }}</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--tsel-dark-blue);">
                                        Rp {{ number_format($tx->produk->produk_harga_akhir ?? $tx->total_harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($tx->is_paid)
                                            <span class="badge-pill badge-success-tsel">
                                                <i class="fas fa-check-circle"></i> Lunas
                                            </span>
                                        @else
                                            <span class="badge-pill badge-warning-tsel">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="color: #9CA3AF; padding: 2rem;">
                                        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i> Belum ada transaksi tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
</x-kasir.layouts>

<script>
    function updateClock() {
        const timeElement = document.getElementById('live-time');
        const dateElement = document.getElementById('live-date');
        if (!timeElement || !dateElement) return;

        const now = new Date();
        
        // Time format
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}:${seconds}`;

        // Date format (Indonesian style)
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        const dayName = days[now.getDay()];
        const day = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        
        dateElement.textContent = `${dayName}, ${day} ${monthName} ${year}`;
    }

    // Run clock immediately and every second
    setInterval(updateClock, 1000);
    updateClock();
</script>
