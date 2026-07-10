<?php

namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class PelangganController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $produks = Produk::where('produk_stok', '>', 0)
            ->whereNull('travel_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pelanggan.home', compact('produks'));
    }

    public function showProduk($id)
    {
        $produk = Produk::whereNull('travel_id')->findOrFail($id);
        return view('pelanggan.detail-produk', compact('produk'));
    }

    public function beliProduk($id)
    {
        $produk = Produk::whereNull('travel_id')->findOrFail($id);
        return view('pelanggan.beli-produk', compact('produk'));
    }

    public function processTransaksi(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'nomor_telepon' => 'required|regex:/^[0-9]{10,13}$/',
            'aktivasi_tanggal' => 'required|date|after_or_equal:today'
        ]);

        $produk = Produk::whereNull('travel_id')->findOrFail($request->produk_id);
        
        if ($produk->produk_stok < 1) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        DB::beginTransaction();
        try {
            $harga_setelah_diskon = $produk->produk_harga - ($produk->produk_harga * $produk->produk_diskon / 100);
            $total = $harga_setelah_diskon * 1;

            $id_transaksi = 'TRX-PLG-' . Auth::id() . '-' . time() . '-' . rand(1000, 9999);

            $transaksi = new Transaksi();
            $transaksi->id_transaksi = $id_transaksi;
            $transaksi->id_pelanggan = Auth::id();
            $transaksi->produk_id = $produk->id;
            $transaksi->jumlah = 1;
            $transaksi->total_harga = $total;
            $transaksi->status = 'pending';
            $transaksi->nama_pelanggan = Auth::user()->name;
            $transaksi->telepon_pelanggan = $request->nomor_telepon;
            $transaksi->nomor_roaming = $request->nomor_telepon; // Nomor yang akan di-roaming
            $transaksi->aktivasi_tanggal = $request->aktivasi_tanggal;
            $transaksi->tanggal_transaksi = now();
            $transaksi->save();

            $produk->produk_stok -= 1;
            $produk->save();

            DB::commit();

            return redirect()->route('pelanggan.pembayaran', $transaksi->id)
                ->with('success', 'Transaksi berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayatTransaksi()
    {
        $pendingTransaksis = Transaksi::where('id_pelanggan', Auth::id())
            ->where('status', 'pending')
            ->whereNotNull('id_transaksi')
            ->get();

        foreach ($pendingTransaksis as $trx) {
            try {
                $status = \Midtrans\Transaction::status($trx->id_transaksi);
                if ($status && isset($status->transaction_status)) {
                    $ts = $status->transaction_status;
                    if ($ts === 'capture' || $ts === 'settlement') {
                        $trx->status = 'lunas';
                        $trx->is_paid = true;
                        $trx->metode_pembayaran = ($status->payment_type ?? 'QRIS');
                        $trx->save();

                        // Kurangi stok jika belum
                        if ($trx->produk) {
                            $alreadyRecorded = \App\Models\StockHistory::where('product_id', $trx->produk_id)
                                ->where('action', 'Penjualan (Online)')
                                ->where('created_at', '>=', now()->subMinutes(10))
                                ->exists();

                            if (!$alreadyRecorded) {
                                \App\Models\StockHistory::create([
                                    'product_id' => $trx->produk_id,
                                    'change_amount' => $trx->jumlah ?? 1,
                                    'previous_stock' => $trx->produk->produk_stok + ($trx->jumlah ?? 1),
                                    'current_stock' => $trx->produk->produk_stok,
                                    'action' => 'Penjualan (Online)',
                                ]);
                            }
                        }
                    } elseif ($ts === 'deny' || $ts === 'expire' || $ts === 'cancel') {
                        $trx->status = 'batal';
                        $trx->save();

                        // Kembalikan stok
                        if ($trx->produk) {
                            $oldStock = $trx->produk->produk_stok;
                            $trx->produk->increment('produk_stok', $trx->jumlah ?? 1);
                            
                            \App\Models\StockHistory::create([
                                'product_id' => $trx->produk->id,
                                'change_amount' => $trx->jumlah ?? 1,
                                'previous_stock' => $oldStock,
                                'current_stock' => $trx->produk->produk_stok,
                                'action' => 'Pembatalan (Stok Kembali)',
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Not found di Midtrans atau error API (bisa diabaikan)
            }
        }

        $transaksis = Transaksi::where('id_pelanggan', Auth::id())
            ->whereIn('status', ['pending', 'lunas', 'success'])
            ->with('produk')
            ->orderByDesc('id')
            ->get();
        
        return view('pelanggan.riwayat-transaksi', compact('transaksis'));
    }

    public function profil()
    {
        $user = Auth::user();
        return view('pelanggan.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^08(11|12|13|21|22|23|51|52|53)[0-9]{5,9}$/'],
            'email' => 'required|email|max:255|unique:role_users,email,' . Auth::id(),
            'tempat_tugas' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama harus diisi',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.regex' => 'Nomor telepon harus diawali dengan prefix Telkomsel (contoh: 0812, 0813, 0821, 0852, dll)',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        try {
            $user = Auth::user();
            
            // Cek apakah data profil (khususnya nomor telepon) sebelumnya kosong
            $wasIncomplete = empty($user->phone);

            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->tempat_tugas = $request->tempat_tugas;
            $user->save();

            if ($wasIncomplete) {
                return redirect()->route('pelanggan.home')
                    ->with('success', 'Profil berhasil dilengkapi! Silakan pilih paket kuota haji Anda.');
            }

            return back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error update profil: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function pembayaran($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('id_pelanggan', Auth::id())
            ->with('produk')
            ->firstOrFail();
        
        if ($transaksi->status == 'lunas' || $transaksi->is_paid) {
            return redirect()->route('pelanggan.riwayat-transaksi')
                ->with('info', 'Transaksi ini sudah dibayar.');
        }

        try {
            // Cek apakah sudah ada snap token untuk transaksi ini
            if (!empty($transaksi->snap_token)) {
                $snapToken = $transaksi->snap_token;
            } else {
                $params = [
                    'transaction_details' => [
                        'order_id' => $transaksi->id_transaksi,
                        'gross_amount' => (int) $transaksi->total_harga,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone ?? '081234567890',
                    ],
                    'item_details' => [
                        [
                            'id' => $transaksi->produk_id,
                            'price' => (int) $transaksi->produk->produk_harga,
                            'quantity' => $transaksi->jumlah,
                            'name' => $transaksi->produk->produk_nama,
                        ]
                    ],
                    'callbacks' => [
                        'finish' => route('pelanggan.pembayaran.callback', $transaksi->id),
                        'unfinish' => route('pelanggan.riwayat-transaksi'),
                        'error' => route('pelanggan.riwayat-transaksi')
                    ]
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaksi->snap_token = $snapToken;
                $transaksi->save();
            }
            
            return view('pelanggan.pembayaran', compact('transaksi', 'snapToken'));

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function callbackPembayaran(Request $request, $id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();
        
        try {
            $status = \Midtrans\Transaction::status($transaksi->id_transaksi);
            
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $transaksi->status = 'lunas';
                $transaksi->is_paid = true;
                $transaksi->metode_pembayaran = ($status->payment_type ?? 'QRIS');
                $transaksi->save();
                
                // Record Stock History for Sale (if not already recorded by notification handler)
                $alreadyRecorded = \App\Models\StockHistory::where('product_id', $transaksi->produk_id)
                    ->where('action', 'Penjualan (Online)')
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->exists();

                if (!$alreadyRecorded && $transaksi->produk) {
                    \App\Models\StockHistory::create([
                        'product_id' => $transaksi->produk_id,
                        'change_amount' => $transaksi->jumlah,
                        'previous_stock' => $transaksi->produk->produk_stok + $transaksi->jumlah,
                        'current_stock' => $transaksi->produk->produk_stok,
                        'action' => 'Penjualan (Online)',
                    ]);
                }

                return redirect()->route('pelanggan.riwayat-transaksi')
                    ->with('success', 'Pembayaran berhasil! Terima kasih atas pembelian Anda.');
            } else {
                return redirect()->route('pelanggan.riwayat-transaksi')
                    ->with('warning', 'Pembayaran belum selesai. Status: ' . $status->transaction_status);
            }
            
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return redirect()->route('pelanggan.riwayat-transaksi')
                ->with('error', 'Gagal verifikasi pembayaran.');
        }
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status ?? 'accept';

            Log::info('Midtrans Notification: ' . json_encode([
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus
            ]));

            // Cek apakah ini transaksi kolektif travel agent
            $isKolektif = str_starts_with($orderId, 'TRV-KOL-');

            if ($isKolektif) {
                $transaksis = Transaksi::where('id_transaksi', 'like', $orderId . '%')->get();
                if ($transaksis->isEmpty()) {
                    return response()->json(['status' => 'error', 'message' => 'Transactions not found'], 404);
                }
                $statusLunas = 'completed';
            } else {
                $transaksi = Transaksi::where('id_transaksi', $orderId)->first();
                if (!$transaksi) {
                    return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
                }
                $transaksis = collect([$transaksi]);
                $statusLunas = 'lunas';
            }

            foreach ($transaksis as $transaksi) {
                $oldStatus = $transaksi->status;
                $isSales = !empty($transaksi->nama_sales);

                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    if ($transactionStatus == 'capture' && $fraudStatus != 'accept') {
                        continue;
                    }
                    
                    if ($oldStatus !== $statusLunas) {
                        $transaksi->status = $statusLunas;
                        $transaksi->is_paid = true;
                        $transaksi->metode_pembayaran = ($notification->payment_type ?? 'QRIS');
                        
                        // Record Stock History for Sale, but ONLY if not Sales (Sales already recorded it during submit)
                        if ($transaksi->produk && !$isSales) {
                            \App\Models\StockHistory::create([
                                'product_id' => $transaksi->produk_id,
                                'change_amount' => $transaksi->jumlah ?? 1,
                                'previous_stock' => $transaksi->produk->produk_stok + ($transaksi->jumlah ?? 1),
                                'current_stock' => $transaksi->produk->produk_stok,
                                'action' => $isKolektif ? 'Penjualan (Kolektif Online)' : 'Penjualan (Online)',
                            ]);
                        }
                    }
                } else if ($transactionStatus == 'pending') {
                    $transaksi->status = 'pending';
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    if ($oldStatus !== 'batal') {
                        $transaksi->status = 'batal';

                        // Kembalikan stok jika ini pembelian kolektif travel, pelanggan online, atau sales
                        if ($transaksi->produk) {
                            $produk = $transaksi->produk;
                            $oldStock = $produk->produk_stok;
                            $produk->increment('produk_stok', $transaksi->jumlah ?? 1);
                            
                            // Hanya kolektif dan sales yang record produk_terjual sebelumnya saat di controller submit/kolektif
                            // Online juga decrement produk_stok di controller, tapi tidak produk_terjual. 
                            // Untuk amannya, kita bisa asumsikan:
                            if ($isKolektif || $isSales) {
                                $produk->decrement('produk_terjual', $transaksi->jumlah ?? 1);
                            }

                            $actionMessage = 'Pembatalan (Stok Kembali)';
                            if ($isKolektif) $actionMessage = 'Pembatalan Kolektif (Stok Kembali)';
                            if ($isSales) $actionMessage = 'Pembatalan Sales (Stok Kembali)';

                            \App\Models\StockHistory::create([
                                'product_id' => $produk->id,
                                'change_amount' => $transaksi->jumlah ?? 1,
                                'previous_stock' => $oldStock,
                                'current_stock' => $produk->produk_stok,
                                'action' => $actionMessage,
                            ]);
                        }
                    }
                }

                $transaksi->save();
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function batalkanTransaksi($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::where('id', $id)
                ->where('id_pelanggan', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $produk = Produk::find($transaksi->produk_id);
            if ($produk) {
                $oldStock = $produk->produk_stok;
                $produk->produk_stok += $transaksi->jumlah;
                $produk->save();

                // Record Stock History for Cancellation (Stock Restored)
                \App\Models\StockHistory::create([
                    'product_id' => $produk->id,
                    'change_amount' => $transaksi->jumlah,
                    'previous_stock' => $oldStock,
                    'current_stock' => $produk->produk_stok,
                    'action' => 'Pembatalan (Stok Kembali)',
                ]);
            }

            $transaksi->status = 'batal';
            $transaksi->save();

            DB::commit();

            return redirect()->route('pelanggan.riwayat-transaksi')
                ->with('success', 'Transaksi berhasil dibatalkan. Stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error membatalkan transaksi: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    public function downloadNota($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();

        $produk = $transaksi->produk;
        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $formData = [
            'icon' => asset('admin_asset/img/photos/icon_telkomsel.png'),
            'logo' => asset('admin_asset/img/photos/logo_telkomsel.png'),
            'id_transaksi' => $transaksi->id_transaksi,
            'produk_nama' => $produk->produk_nama,
            'produk_harga' => $produk->produk_harga,
            'produk_harga_akhir' => $produk->produk_harga - ($produk->produk_harga * $produk->produk_diskon / 100),
            'kuota' => $produk->kuota ?? 'Utama',
            'masa_aktif' => $produk->masa_aktif ?? '30 Hari',
            'merch_nama' => 'Paket Haji',
            'nama_pelanggan' => $transaksi->nama_pelanggan,
            'nama_sales' => 'Admin',
            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            'telepon_pelanggan' => $transaksi->telepon_pelanggan,
            // 'nomor_telepon' => '0811-1234-5678', // Nomor CS Admin
            'metode_pembayaran' => $transaksi->metode_pembayaran,
            'aktivasi_tanggal' => \Carbon\Carbon::parse($transaksi->aktivasi_tanggal)->format('Y-m-d'),
        ];

        // Return HTML untuk AJAX request (modal)
        if (request()->ajax()) {
            return view('pelanggan.nota-preview', ['formData' => $formData]);
        }

        $pdf = Pdf::loadView('kasir.kwitansi', ['formData' => $formData])
            ->setPaper('A6', 'portrait');

        $filename = 'nota-' . $transaksi->id_transaksi . '.pdf';
        
        if (request()->has('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }
}








