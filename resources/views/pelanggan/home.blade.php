<x-pelanggan.layouts>
    <style>
        :root {
            --tsel-primary: #bc0007;      /* Dari login page */
            --tsel-primary-light: #e2241d; /* Dari login page */
            --tsel-dark: #1a1c1c;         /* Text dark */
            --tsel-gray: #f9f9f9;         /* Surface */
            --tsel-border: #e2e2e2;       /* Surface variant */
            --tsel-text-muted: #5d5e60;   /* Secondary */
        }

        body {
            background-color: var(--tsel-gray);
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--tsel-primary) 0%, var(--tsel-primary-light) 100%);
            border-radius: 16px;
            padding: 40px;
            color: #fff;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(188, 0, 7, 0.2);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        /* Subtle Islamic Pattern overlay for banner */
        .welcome-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l2.5 12.5L45 15l-12.5 2.5L30 30l-2.5-12.5L15 15l12.5-2.5L30 0zm0 30l2.5 12.5L45 45l-12.5 2.5L30 60l-2.5-12.5L15 45l12.5-2.5L30 30z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }

        .welcome-banner-content {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .welcome-banner h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .welcome-banner p {
            font-size: 1.1rem;
            margin-bottom: 0;
            opacity: 0.9;
            font-weight: 400;
            line-height: 1.6;
        }

        .welcome-icon {
            font-size: 5rem;
            color: #fff;
            opacity: 0.1;
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 0;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--tsel-dark);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--tsel-primary);
        }

        .category-wrapper {
            margin-bottom: 40px;
        }

        .category-title {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-left: 15px;
            border-left: 4px solid var(--tsel-primary);
        }

        .category-title h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tsel-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-title h3 i {
            color: var(--tsel-primary);
            font-size: 1.3rem;
        }

        .badge-count {
            background: #f1f1f1;
            color: var(--tsel-text-muted);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 15px;
            border: 1px solid var(--tsel-border);
        }

        .product-card-link {
            transition: transform 0.2s ease;
            text-decoration: none;
            display: block;
            height: 100%;
        }
        .product-card-link:hover {
            transform: scale(1.02);
            text-decoration: none;
        }

        .product-card-white {
            background: #fff;
            border-radius: 16px;
            padding: 16px 16px 12px 16px;
            margin-bottom: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .out-of-stock {
            opacity: 0.7;
            filter: grayscale(100%);
        }

        .card-badges {
            display: flex;
            position: absolute;
            top: 0;
            left: 0;
            overflow: hidden;
            border-top-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .badge-primary {
            background: #bc0007; /* Telkomsel Red */
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 4px 12px;
        }

        .badge-secondary {
            background: #475569;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 4px 12px;
        }

        .card-main-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-icon {
            color: #bc0007; /* Telkomsel Red */
            font-size: 1.2rem;
        }

        .package-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
            word-break: break-word;
        }

        .package-duration {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
            white-space: nowrap;
        }

        .detail-box {
            background: #fdf2f2; /* Light Red */
            border-radius: 8px;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }

        .detail-box i {
            color: #10b981; /* Green check */
            font-size: 0.85rem;
        }

        .detail-box span {
            font-size: 0.75rem;
            color: #334155;
            font-weight: 500;
        }

        .card-divider {
            border: 0;
            height: 1px;
            background: #f1f5f9;
            margin: auto 0 12px 0;
        }

        .card-footer-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-container {
            display: flex;
            flex-direction: column;
        }

        .original-price-strike {
            font-size: 0.7rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .final-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1e293b; /* Dark Navy/Black */
        }

        .action-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #bc0007;
            font-size: 0.8rem;
        }

        .out-of-stock-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(1px);
        }

        .out-of-stock-overlay span {
            background: #1e293b;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .info-banner {
            background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
            border: 1px solid rgba(188,0,7,0.1);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 10px 30px rgba(188, 0, 7, 0.05);
            position: relative;
            overflow: hidden;
        }

        .info-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--tsel-primary) 0%, var(--tsel-primary-light) 100%);
        }

        .info-banner h3 {
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 1.4rem;
            color: var(--tsel-dark);
        }

        .info-banner p {
            font-size: 1rem;
            margin-bottom: 0;
            line-height: 1.6;
            color: var(--tsel-text-muted);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px dashed #ccc;
            margin-bottom: 30px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            color: var(--tsel-dark);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--tsel-text-muted);
        }

        @media (max-width: 768px) {
            .welcome-banner {
                padding: 20px;
                text-align: center;
                margin-bottom: 25px;
                border-radius: 12px;
            }
            .welcome-icon {
                display: none;
            }
            .welcome-banner h1 {
                font-size: 1.35rem;
                margin-bottom: 10px;
            }
            .welcome-banner p {
                font-size: 0.9rem;
                line-height: 1.4;
            }
            .section-title {
                font-size: 1.2rem;
                margin-bottom: 20px;
            }
            .category-title {
                margin-bottom: 15px;
            }
            .category-title h3 {
                font-size: 1.1rem;
            }
            .product-card {
                padding: 16px;
            }
            .product-icon {
                width: 44px;
                height: 44px;
                font-size: 1.3rem;
                margin-bottom: 15px;
            }
            .product-title {
                font-size: 0.95rem;
                min-height: auto;
                margin-bottom: 10px;
            }
            .product-detail {
                padding: 12px;
            }
            .product-detail-label, .product-detail-value {
                font-size: 0.8rem;
            }
            .price-current {
                font-size: 1.2rem;
            }
            .discount-badge {
                font-size: 0.7rem;
                padding: 3px 8px;
            }
            .btn-buy {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            .stock-badge {
                font-size: 0.75rem;
            }
            .stock-badge-container {
                position: relative;
                top: 0;
                right: 0;
                margin-bottom: 15px;
                display: flex;
                justify-content: flex-start;
            }
            .info-banner {
                padding: 20px;
                margin-top: 25px;
            }
            .info-banner h3 {
                font-size: 1.1rem;
            }
            .info-banner p {
                font-size: 0.85rem;
            }
        }

        /* Background Decorations */
        .bg-decorations {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }

        .bg-icon {
            position: absolute;
            color: var(--tsel-primary);
            opacity: 0.03;
            animation: float-icon 8s ease-in-out infinite;
        }

        .icon-1 { top: 15%; left: 5%; font-size: 8rem; animation-delay: 0s; }
        .icon-2 { top: 60%; right: 5%; font-size: 16rem; animation-delay: -2s; }
        .icon-3 { bottom: 10%; left: 15%; font-size: 6rem; animation-delay: -4s; }
        .icon-4 { top: 25%; right: 20%; font-size: 5rem; animation-delay: -1s; }
        .icon-5 { top: 45%; left: 30%; font-size: 4rem; animation-delay: -3s; }

        @keyframes float-icon {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
    </style>

    <!-- Background Decorations -->
    <div class="bg-decorations">
        <i class="fas fa-plane-departure bg-icon icon-1"></i>
        <i class="fas fa-kaaba bg-icon icon-2"></i>
        <i class="fas fa-moon bg-icon icon-3"></i>
        <i class="fas fa-star bg-icon icon-4"></i>
        <i class="fas fa-cloud bg-icon icon-5"></i>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-banner-content">
            <h1><i class="fas fa-kaaba"></i> Assalamu'alaikum, {{ Auth::user()->name }}</h1>
            <p>Portal Pelanggan <strong>Telkomsel RoaMAX Haji</strong>. Tersedia berbagai pilihan paket kuota untuk kebutuhan komunikasi Anda di Tanah Suci.</p>
        </div>
        <i class="fas fa-mosque welcome-icon"></i>
    </div>

    <!-- Products Section -->
    <h2 class="section-title">
        <i class="fas fa-box"></i> Pilihan Paket RoaMAX Haji
    </h2>

    @if($produks->count() > 0)
        <div class="row">
            @foreach($produks as $produk)
            <div class="col-lg-6 col-md-12 mb-4 d-flex align-items-stretch">
                <a href="{{ $produk->produk_stok > 0 ? route('pelanggan.produk.beli', $produk->id) : '#' }}" class="product-card-link w-100">
                    <div class="product-card-white {{ $produk->produk_stok < 1 ? 'out-of-stock' : '' }}">
                        
                        <div class="card-badges">
                            <span class="badge-primary">RoaMAX</span>
                            @if($produk->produk_diskon > 0)
                                <span class="badge-secondary">Hemat Rp {{ number_format($produk->produk_diskon / 1000, 0) }}k</span>
                            @endif
                        </div>

                        <div class="card-main-info" style="margin-bottom: 8px;">
                            <div class="title-wrapper">
                                <i class="fas fa-sim-card main-icon"></i>
                                <h3 class="package-title">{{ $produk->produk_nama }}</h3>
                            </div>
                            <span class="package-duration">{{ $produk->masa_aktif ?? '30 Hari' }}</span>
                        </div>

                        @if($produk->produk_detail)
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0; margin-bottom: 12px; line-height: 1.4; padding-left: 2px;">
                            {{ $produk->produk_detail }}
                        </div>
                        @else
                        <div style="height: 4px;"></div>
                        @endif

                        <div class="detail-box" style="margin-top: auto;">
                            <i class="fas fa-check-circle"></i> 
                            <span>Kuota {{ $produk->kuota ?? 'Utama' }}</span>
                        </div>
                        <div class="detail-box" style="margin-top: auto;">
                            <i class="fas fa-check-circle"></i> 
                            <span>Masa Aktif {{ $produk->masa_aktif ?? 'Utama' }}</span>
                        </div>

                        <hr class="card-divider">

                        <div class="card-footer-flex">
                            <div class="price-container">
                                @if($produk->produk_diskon > 0)
                                    @php $harga_setelah_diskon = $produk->produk_harga - $produk->produk_diskon; @endphp
                                    <span class="original-price-strike">Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}</span>
                                    <span class="final-price">Rp {{ number_format($harga_setelah_diskon, 0, ',', '.') }}</span>
                                @else
                                    <span class="final-price">Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="background: #bc0007; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">Beli</span>
                                <div class="action-icon">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                        
                        @if($produk->produk_stok < 1)
                        <div class="out-of-stock-overlay">
                            <span>Stok Habis</span>
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>

    @else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Belum ada produk</h3>
        <p>Silakan cek kembali halaman ini nanti untuk produk paket RoaMAX Haji terbaru.</p>
    </div>
    @endif

    <!-- Info Banner -->
    <div class="info-banner">
        <h3><i class="fas fa-headset" style="color: var(--tsel-primary);"></i> Pusat Bantuan</h3>
        <p>Hubungi CS kami di <strong>188</strong> (Bebas Pulsa) atau email ke <strong>cs@telkomsel.co.id</strong></p>
    </div>

</x-pelanggan.layouts>

