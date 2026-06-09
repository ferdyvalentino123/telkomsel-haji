<x-kasir.layouts><main class="content"><div class="container-fluid p-0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --tsel-red: #EC1C24;
            --tsel-dark: #1A1A1A;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        .content-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 0% 0%, #fff5f5 0%, #f8f9fa 100%);
            min-height: 100vh;
            padding: 2rem;
            color: var(--tsel-dark);
        }

        .welcome-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .welcome-glass::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(236, 28, 36, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: 0;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-glass h1 {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1A1A1A 0%, #4A4A4A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -2px;
        }

        .welcome-glass p {
            font-size: 1.4rem;
            color: #666;
            font-weight: 500;
            margin-bottom: 2.5rem;
        }

        .btn-premium {
            background: var(--tsel-red);
            color: white;
            padding: 1.2rem 2.5rem;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 20px rgba(236, 28, 36, 0.2);
        }

        .btn-premium:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(236, 28, 36, 0.3);
            color: white;
        }

        .welcome-img-wrapper {
            position: relative;
            z-index: 1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .welcome-img {
            max-width: 220px;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
        }

        .grid-dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .card-aesthetic {
            background: white;
            border-radius: 28px;
            padding: 2.5rem;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-aesthetic:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.08);
            border-color: rgba(236, 28, 36, 0.1);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .card-aesthetic:hover .card-icon {
            transform: rotate(-10deg) scale(1.1);
        }

        .label-aesthetic {
            font-size: 0.9rem;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.5rem;
        }

        .value-aesthetic {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--tsel-dark);
            margin-bottom: 1.5rem;
        }

        .footer-aesthetic {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-weight: 600;
            font-size: 0.95rem;
            padding-top: 1.5rem;
            border-top: 1px dashed #eee;
        }

        .trend-up {
            color: #10B981;
            background: #ECFDF5;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        @media (max-width: 1200px) {
            .grid-dashboard { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .grid-dashboard { grid-template-columns: 1fr; }
            .welcome-glass { padding: 2.5rem; flex-direction: column; text-align: center; }
            .welcome-glass h1 { font-size: 2.5rem; }
            .welcome-img { max-width: 150px; margin-top: 2rem; }
        }
    </style>

    <div class="content-wrapper">
        <div class="container-fluid p-0">
            <!-- Glassmorphism Welcome -->
            <div class="welcome-glass">
                <div class="welcome-content">
                    <h1>Hai, {{ Auth::user()->name }}!</h1>
                    <p>Dashboard Kasir RoaMAX Haji & Umrah Telkomsel</p>
                    <a href="{{ route('kasir.transaksi.approve') }}" class="btn-premium">
                        <i class="fas fa-rocket"></i>
                        <span>Approval Cepat</span>
                    </a>
                </div>
                <div class="welcome-img-wrapper">
                    <img src="{{ asset('admin_asset/img/photos/icon_spv.png') }}" alt="User" class="welcome-image welcome-img">
                </div>
            </div>

            <!-- Aesthetic Grid -->
            <div class="grid-dashboard">
                <!-- Budget Card -->
                <a href="{{ route('kasir.budget_insentif.index') }}" class="card-aesthetic shadow-sm">
                    <div>
                        <div class="card-icon" style="background: #FFF5F5; color: #EC1C24;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="label-aesthetic">Sisa Budget Insentif</div>
                        <div class="value-aesthetic" style="color: #EC1C24;">
                            Rp {{ number_format($sisaBudget) }}
                        </div>
                    </div>
                    <div class="footer-aesthetic">
                        <span class="trend-up"><i class="fas fa-arrow-up me-1"></i> Aktif</span>
                        <span>Total: Rp {{ number_format($totalBudget) }}</span>
                    </div>
                </a>

                <!-- Setoran Card -->
                <a href="{{ route('kasir.monitor.setoran') }}" class="card-aesthetic shadow-sm">
                    <div>
                        <div class="card-icon" style="background: #EEF2FF; color: #4F46E5;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="label-aesthetic">Monitor Setoran</div>
                        <div class="value-aesthetic" style="color: #4F46E5;">
                            {{ $totalSales ?? 0 }} <span style="font-size: 1rem; font-weight: 500;">Sales Aktif</span>
                        </div>
                    </div>
                    <div class="footer-aesthetic">
                        <i class="fas fa-sync-alt fa-spin me-2 text-primary"></i> Real-time tracking
                    </div>
                </a>

                <!-- Riwayat Card -->
                <a href="{{ route('kasir.transaksi.index') }}" class="card-aesthetic shadow-sm">
                    <div>
                        <div class="card-icon" style="background: #FFFBEB; color: #D97706;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="label-aesthetic">Riwayat Transaksi</div>
                        <div class="value-aesthetic" style="color: #D97706;">
                            {{ number_format($totalTransaksi ?? 0) }} <span style="font-size: 1rem; font-weight: 500;">Berhasil</span>
                        </div>
                    </div>
                    <div class="footer-aesthetic">
                        <i class="fas fa-calendar-alt me-2 text-warning"></i> Cek laporan harian
                    </div>
                </a>
            </div>
        </div>
    </div>
</div></main></x-kasir.layouts>


