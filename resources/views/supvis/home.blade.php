<x-Supvis.SupvisLayouts>
    <main class="content">
        @if (Auth::user()->is_superuser)
            <li class="list-group-item">
                <a href="a">Menu tambahan khusus Superuser</a>
            </li>
        @endif

        <div class="container" style="text-align: center; padding: 18px; font-family: 'Poppins', sans-serif;">
            <div class="welcome-card">
                <div class="welcome-text">
                    <h1>Hai, {{ Auth::user()->name }}! 👋</h1>
                    <p>Selamat datang dan beraktivitas kembali!</p>
                </div>
                <div>
                    <img src="{{ asset('admin_asset/img/photos/icon_spv.png') }}" alt="Illustration" class="welcome-image">
                </div>
            </div>

            <div class="dashboard-container">

            <a href="{{ route('kasir.budget_insentif.index') }}" style="text-decoration: none;">
                <div class="info-box budget-box">
                    <h3>Sisa Budget:</h3>
                    <p class="amount">IDR {{ number_format($sisaBudget) }}</p>
                    <p class="percentage">dari Total Insentif keluar sebesar {{ number_format($totalInsentif) }}</p>
                </div>
            </a>
        </div>
    </main>

    <style>
    body {
        background-color: #f9f9f9;
        overflow: hidden;
    }

    .welcome-card {
        display: flex;
        align-items: center;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    .welcome-text {
        flex: 1;
        text-align: left;
        margin-right: 15px;
    }

    .welcome-text h1 {
        font-size: 2rem;
    }

    .welcome-text p {
        font-size: 1.2rem;
    }

    .welcome-image {
        max-width: 100px;
        height: auto;
        border-radius: 12px;
    }

    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-box {
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, transform 0.3s;
    }

    .info-box:hover {
        background-color: #f1f1f1;
        transform: translateY(-5px);
    }

    .amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #4caf50;
    }

    .amount.negative {
        color: #f44336;
    }

    .percentage {
        font-size: 0.9rem;
        color: #555;
    }

    .budget-box {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .welcome-card {
            flex-direction: column;
            text-align: center;
            padding: 25px;
        }

        .welcome-text {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .welcome-text h1 {
            font-size: 1.8rem;
        }

        .welcome-text p {
            font-size: 1.1rem;
        }

        .welcome-image {
            max-width: 80px;
        }

        .dashboard-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .info-box {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .welcome-card {
            padding: 20px;
        }

        .welcome-text h1 {
            font-size: 1.6rem;
        }

        .welcome-text p {
            font-size: 1rem;
        }

        .welcome-image {
            max-width: 70px;
        }

        .info-box {
            padding: 12px;
        }

        .info-box h3 {
            font-size: 1rem;
        }

        .info-box p {
            font-size: 0.9rem;
        }

        .dashboard-container {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    </style>
</x-Supvis.SupvisLayouts>

