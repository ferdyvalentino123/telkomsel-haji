<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Approved Transaksi</h1>
    <div class="table-container">
       <!-- resources/views/pdf/approved_transaksi.blade.php -->
<table>
    <thead>
        <tr>
            <th>ID Transaksi</th>
            <th>Kasir</th>
            <th>Tempat Bertugas</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>No. Injeksi</th>
            <th>Aktivasi Tanggal</th>
            <th>Jenis Paket</th>
            <th>Merchandise</th>
            <th>Harga</th>
            <th>Pembayaran</th>
            <th>Setor</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
        <tr>
            <td>{{ $transaction->id_transaksi }}</td>
            <td>{{ $transaction->nama_sales }}</td>
            <td>{{ $transaction->tempat_tugas ?? '-' }}</td>
            <td>{{ $transaction->tanggal_transaksi }}</td>
            <td>{{ $transaction->nama_pelanggan }}</td>
            <td>{{ $transaction->nomor_telepon }}</td>
            <td>{{ $transaction->nomor_injeksi }}</td>
            <td>{{ $transaction->aktivasi_tanggal }}</td>
            <td>{{ $transaction->jenis_paket }}</td>
            <td>{{ is_array($transaction->merchandise) ? implode(', ', $transaction->merchandise) : $transaction->merchandise }}</td>
            <td>{{ $transaction->harga ?? '-' }}</td>
            <td>{{ $transaction->metode_pembayaran }}</td>
            <td>{{ is_array($transaction->history_setoran) ? implode(', ', $transaction->history_setoran) : $transaction->history_setoran }}</td>
            <td>{{ $transaction->status }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

    </div>
</body>
</html>

