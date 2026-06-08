<?php
namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Transaction;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Real-time status sync with Midtrans for pending transactions
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = config('services.midtrans.is_sanitized');
            Config::$is3ds = config('services.midtrans.is_3ds');

            $orderId = $request->query('order_id');
            $groupsToCheck = [];
            
            if ($orderId) {
                if (str_starts_with($orderId, 'TRV-KOL-')) {
                    $groupsToCheck[] = $orderId;
                }
            }

            // Find other pending transactions of this user created in the last 2 hours
            $recentPendings = Transaksi::where('id_supervisor', Auth::id())
                ->where('status', 'pending')
                ->whereNotNull('snap_token')
                ->where('created_at', '>=', now()->subHours(2))
                ->get();

            foreach ($recentPendings as $rp) {
                $parts = explode('-', $rp->id_transaksi);
                if (count($parts) > 1 && str_starts_with($rp->id_transaksi, 'TRV-KOL-')) {
                    array_pop($parts); // Remove the index part (-0, -1, etc)
                    $gId = implode('-', $parts);
                    if (!in_array($gId, $groupsToCheck)) {
                        $groupsToCheck[] = $gId;
                    }
                } else {
                    if (!in_array($rp->id_transaksi, $groupsToCheck)) {
                        $groupsToCheck[] = $rp->id_transaksi;
                    }
                }
            }

            // Query Midtrans status for each unique group and sync status
            foreach ($groupsToCheck as $gId) {
                try {
                    $status = Transaction::status($gId);
                    $transactionStatus = $status->transaction_status;
                    $fraudStatus = $status->fraud_status ?? 'accept';

                    if (str_starts_with($gId, 'TRV-KOL-')) {
                        $matchedTransactions = Transaksi::where('id_transaksi', 'like', $gId . '%')->get();
                    } else {
                        $matchedTransactions = Transaksi::where('id_transaksi', $gId)->get();
                    }

                    foreach ($matchedTransactions as $transaksi) {
                        $oldStatus = $transaksi->status;
                        
                        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                            if ($transactionStatus == 'capture' && $fraudStatus != 'accept') {
                                continue;
                            }
                            
                            if ($oldStatus !== 'completed') {
                                $transaksi->status = 'completed';
                                $transaksi->is_paid = true;
                                $transaksi->metode_pembayaran = ($status->payment_type ?? 'QRIS');
                                $transaksi->save();

                                // Record Stock History for Sale if not already recorded
                                $alreadyRecorded = \App\Models\StockHistory::where('product_id', $transaksi->produk_id)
                                    ->where('action', 'Penjualan (Kolektif Online)')
                                    ->where('created_at', '>=', $transaksi->created_at)
                                    ->exists();

                                if (!$alreadyRecorded && $transaksi->produk) {
                                    \App\Models\StockHistory::create([
                                        'product_id' => $transaksi->produk_id,
                                        'change_amount' => 1,
                                        'previous_stock' => $transaksi->produk->produk_stok + 1,
                                        'current_stock' => $transaksi->produk->produk_stok,
                                        'action' => 'Penjualan (Kolektif Online)',
                                    ]);
                                }
                            }
                        } else if ($transactionStatus == 'pending') {
                            if ($oldStatus !== 'pending') {
                                $transaksi->status = 'pending';
                                $transaksi->save();
                            }
                        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                            if ($oldStatus !== 'cancelled' && $oldStatus !== 'batal') {
                                $transaksi->status = 'cancelled';
                                $transaksi->save();

                                // Restore stock
                                if ($transaksi->produk) {
                                    $produk = $transaksi->produk;
                                    $oldStock = $produk->produk_stok;
                                    $produk->increment('produk_stok', 1);
                                    $produk->decrement('produk_terjual', 1);

                                    \App\Models\StockHistory::create([
                                        'product_id' => $produk->id,
                                        'change_amount' => 1,
                                        'previous_stock' => $oldStock,
                                        'current_stock' => $produk->produk_stok,
                                        'action' => 'Pembatalan Kolektif (Stok Kembali)',
                                    ]);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Midtrans check failed for {$gId}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error in TransaksiController realtime sync: " . $e->getMessage());
        }

        // Subquery to get the representative MIN(id) for each grouped checkout session
        $subquery = Transaksi::select(DB::raw('MIN(id) as id'))
            ->where('id_supervisor', Auth::id())
            ->whereHas('produk', function($q) {
                $q->where('travel_id', Auth::id());
            })
            ->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));

        // Filter by search (matches name, phone, or product name in the group)
        if ($request->filled('search')) {
            $search = $request->search;
            $subquery->where(function ($q) use ($search) {
                $q->whereIn(DB::raw('COALESCE(snap_token, id_transaksi)'), function ($inner) use ($search) {
                    $inner->select(DB::raw('COALESCE(snap_token, id_transaksi)'))
                          ->from('transaksis')
                          ->leftJoin('produks', 'transaksis.produk_id', '=', 'produks.id')
                          ->where('transaksis.id_supervisor', Auth::id())
                          ->whereNull('transaksis.deleted_at')
                          ->where(function ($filter) use ($search) {
                              $filter->where('transaksis.nama_pelanggan', 'like', "%{$search}%")
                                     ->orWhere('transaksis.nomor_telepon', 'like', "%{$search}%")
                                     ->orWhere('produks.produk_nama', 'like', "%{$search}%");
                          });
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'pending') {
                $subquery->where('is_paid', false)->where(function ($q) {
                    $q->where('status', 'pending')->orWhereNull('status');
                });
            } elseif ($status === 'confirmed') {
                $subquery->where('status', 'confirmed');
            } elseif ($status === 'completed') {
                $subquery->where(function ($q) {
                    $q->where('is_paid', true)
                      ->orWhere('status', 'completed');
                });
            } elseif ($status === 'cancelled') {
                $subquery->where('status', 'cancelled');
            }
        }

        // Get the list of representative IDs
        $representativeIds = $subquery->pluck('id');

        // Fetch representative transaction records with total counts and accumulated prices
        $query = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_jumlah')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with('produk');

        $transaksi = $query->latest()->paginate(10)->withQueryString();

        return view('travel.transaksi.index', compact('transaksi'));
    }

    public function show(Transaksi $transaksi)
    {
        // Pastikan hanya bisa lihat transaksi milik sendiri
        if ($transaksi->id_supervisor !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Sync status with Midtrans if still pending
        if ($transaksi->status === 'pending' && !empty($transaksi->snap_token)) {
            try {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = config('services.midtrans.is_sanitized');
                Config::$is3ds = config('services.midtrans.is_3ds');

                $orderId = $transaksi->id_transaksi;
                if (str_starts_with($orderId, 'TRV-KOL-')) {
                    $parts = explode('-', $orderId);
                    if (count($parts) > 1) {
                        array_pop($parts);
                        $groupId = implode('-', $parts);
                    } else {
                        $groupId = $orderId;
                    }
                } else {
                    $groupId = $orderId;
                }

                $status = Transaction::status($groupId);
                $transactionStatus = $status->transaction_status;
                $fraudStatus = $status->fraud_status ?? 'accept';

                if (str_starts_with($groupId, 'TRV-KOL-')) {
                    $matchedTransactions = Transaksi::where('id_transaksi', 'like', $groupId . '%')->get();
                } else {
                    $matchedTransactions = Transaksi::where('id_transaksi', $groupId)->get();
                }

                foreach ($matchedTransactions as $t) {
                    $oldStatus = $t->status;
                    
                    if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                        if ($transactionStatus == 'capture' && $fraudStatus != 'accept') {
                            continue;
                        }
                        
                        if ($oldStatus !== 'completed') {
                            $t->status = 'completed';
                            $t->is_paid = true;
                            $t->metode_pembayaran = ($status->payment_type ?? 'QRIS');
                            $t->save();

                            // Record Stock History for Sale if not already recorded
                            $alreadyRecorded = \App\Models\StockHistory::where('product_id', $t->produk_id)
                                ->where('action', 'Penjualan (Kolektif Online)')
                                ->where('created_at', '>=', $t->created_at)
                                ->exists();

                            if (!$alreadyRecorded && $t->produk) {
                                \App\Models\StockHistory::create([
                                    'product_id' => $t->produk_id,
                                    'change_amount' => 1,
                                    'previous_stock' => $t->produk->produk_stok + 1,
                                    'current_stock' => $t->produk->produk_stok,
                                    'action' => 'Penjualan (Kolektif Online)',
                                ]);
                            }
                        }
                    } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                        if ($oldStatus !== 'cancelled' && $oldStatus !== 'batal') {
                            $t->status = 'cancelled';
                            $t->save();

                            // Restore stock
                            if ($t->produk) {
                                $produk = $t->produk;
                                $oldStock = $produk->produk_stok;
                                $produk->increment('produk_stok', 1);
                                $produk->decrement('produk_terjual', 1);

                                \App\Models\StockHistory::create([
                                    'product_id' => $produk->id,
                                    'change_amount' => 1,
                                    'previous_stock' => $oldStock,
                                    'current_stock' => $produk->produk_stok,
                                    'action' => 'Pembatalan Kolektif (Stok Kembali)',
                                ]);
                            }
                        }
                    }
                }
                
                // Refresh the current model
                $transaksi->refresh();

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Midtrans check in show failed for {$transaksi->id_transaksi}: " . $e->getMessage());
            }
        }

        // Fetch all transactions in the same group
        $groupId = $transaksi->snap_token;
        if ($groupId) {
            $grupTransaksi = Transaksi::where('snap_token', $groupId)
                ->where('id_supervisor', Auth::id())
                ->with('produk')
                ->get();
        } else {
            $orderId = $transaksi->id_transaksi;
            if (str_starts_with($orderId, 'TRV-KOL-')) {
                $parts = explode('-', $orderId);
                if (count($parts) > 1) {
                    array_pop($parts);
                    $gId = implode('-', $parts);
                    $grupTransaksi = Transaksi::where('id_transaksi', 'like', $gId . '%')
                        ->where('id_supervisor', Auth::id())
                        ->with('produk')
                        ->get();
                } else {
                    $grupTransaksi = collect([$transaksi]);
                }
            } else {
                $grupTransaksi = collect([$transaksi]);
            }
        }

        $totalHarga = $grupTransaksi->sum('total_harga');
        $transaksi->load('produk');

        return view('travel.transaksi.show', compact('transaksi', 'grupTransaksi', 'totalHarga'));
    }
}