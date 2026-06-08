{{-- @php
    dd($rekapFlat->first());
@endphp --}}

<table style="border:1px solid #000; border-collapse: collapse; width: 100%;">
    @php
        $produkList = collect($rekapFlat)->pluck('produk')->unique()->sort()->values();
        $groupedByTanggal = collect($rekapFlat)->groupBy('tanggal');

        //View Excel UPDATE by VALEN
        $produkColorMap = [
            '15GB 20D INTERNET HAJI' => '#BDD7EE',
            '15GB 20D ROAMAX COMBO' => '#BDD7EE',
            '17GB_COMBO_30D_850000' => '#BDD7EE',
            '23GB 30D INTERNET HAJI' => '#BDD7EE',
            '23GB 30D ROAMAX COMBO' => '#BDD7EE',
            '23GB_COMBO_45D_1010000' => '#BDD7EE',
            '30GB 45D INTERNET HAJI' => '#BDD7EE',
            '30GB 45D ROAMAX COMBO' => '#BDD7EE',
            'MUHAMMADIYAH_COMBO 15GB 20HARI' => '#F8CBAD',
            'MUHAMMADIYAH_COMBO 23GB 30HARI' => '#F8CBAD',
            'MUHAMMADIYAH_COMBO 30GB 45HARI' => '#F8CBAD',
            'MUHAMMADIYAH_INTERNET 15GB 20HARI' => '#F8CBAD',
            'MUHAMMDIYAH_INTERNET 30GB 45HARI' => '#F8CBAD',
            'POSKO_Combo 15GB 20 HARI' => '#4472C4',
            'SHAFIRA_COMBO 15GB 20HARI' => '#BDD7EE',
            'SHAFIRA_COMBO 23GB 30HARI' => '#BDD7EE',
            'SHAFIRA_COMBO 30GB 45HARI' => '#BDD7EE',
            'SHAFIRA_INTERNET 15GB 20HARI' => '#C6E0B4',
            'SHAFIRA_INTERNET 23GB 30HARI' => '#C6E0B4',
            'SHAFIRA_INTERNET 30GB 45HARI' => '#C6E0B4',
            'TAKHOBAR_COMBO 15GB 20HARI' => '#FFF2CC',
            'TAKHOBAR_COMBO 23GB 30HARI' => '#FFF2CC',
            'TAKHOBAR_COMBO 30GB 45HARI' => '#FFF2CC',
            'TAKHOBAR_INTERNET 15GB 20HARI' => '#D9E1F2',
            'TAKHOBAR_INTERNET 23GB 30HARI' => '#D9E1F2',
            'TAKHOBAR_INTERNET 30GB 45HARI' => '#D9E1F2',
        ];
    @endphp

    <thead>
        <tr style="background-color: #4472C4; color: white;">
            <th style="border:1px solid #000; padding: 5px; background-color: #4472C4; text-align: center;">Tanggal</th>
            @foreach ($produkList as $produk)
                @php
                    $bg = $produkColorMap[$produk] ?? '#FFFFFF'; // fallback putih
                @endphp
                <th colspan="2" style="border:1px solid #000; padding: 5px; background-color: {{ $bg }};">
                    {{ $produk }}
                </th>
            @endforeach
            <th colspan="2" style="border:1px solid #000; padding: 5px; background-color: #ffe100;">TOTAL</th>
        </tr>
        <tr style="background-color: #D9E1F2;">
            <th style="border:1px solid #000; padding: 5px;"></th>
            @foreach ($produkList as $produk)
                @php
                    $bg = $produkColorMap[$produk] ?? '#FFFFFF';
                @endphp
                <th style="border:1px solid #000; padding: 5px; background-color: {{ $bg }};">Qty</th>
                <th style="border:1px solid #000; padding: 5px; background-color: {{ $bg }};">Revenue</th>
            @endforeach
            <th style="border:1px solid #000; padding: 5px; background-color: #ffe100">Qty</th>
            <th style="border:1px solid #000; padding: 5px;background-color: #ffe100">Revenue</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($groupedByTanggal as $tanggal => $produkData)
            @php
                $produkByName = collect($produkData)->keyBy('produk');
                $totalQty = 0;
                $totalRevenue = 0;
            @endphp
            <tr>
                <td style="border:1px solid #000; padding: 5px;">
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </td>
                @foreach ($produkList as $produkNama)
                    @php
                        $row = $produkByName[$produkNama] ?? null;
                        $qty = $row['qty'] ?? 0;
                        $revenue = $row['revenue'] ?? 0;
                        $totalQty += $qty;
                        $totalRevenue += $revenue;

                        $bg = $produkColorMap[$produkNama] ?? '#FFFFFF';
                    @endphp
                    <td style="border:1px solid #000; padding: 5px;">
                        {{ $qty }}</td>
                    <td style="border:1px solid #000; padding: 5px;">
                        Rp {{ number_format($revenue, 0, ',', '.') }}
                    </td>
                @endforeach
                <td style="border:1px solid #000; padding: 5px; font-weight: bold;">
                    {{ $totalQty }}
                </td>
                <td style="border:1px solid #000; padding: 5px; font-weight: bold;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

