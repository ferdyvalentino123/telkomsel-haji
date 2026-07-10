<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar transaksi dengan filter tanggal.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with('produk')->latest();

        if ($request->filled('date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('tanggal_transaksi', $request->date)
                  ->orWhereDate('created_at', $request->date);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                  ->orWhere('id_transaksi', 'LIKE', "%{$search}%");
            });
        }

        // Clone query for subquery filtering
        $subquery = clone $query;
        // Remove orders to prevent strict mode group by errors
        $subquery->getQuery()->orders = null;
        $subquery->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));
        // Select MIN(id) to get a representative ID for each group
        $representativeIds = $subquery->selectRaw('MIN(id) as id')->pluck('id');

        // Main query for grouped data
        $groupedQuery = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_msisdn')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with(['produk', 'supervisor', 'pelanggan'])
            ->latest();

        $totalTransaksi = $groupedQuery->count();
        
        // Pendapatan total dihitung dari base query (keseluruhan baris) karena akumulasi_harga adalah raw alias
        $totalPendapatan = (clone $query)->where(function($q) {
            $q->where('is_paid', 1)->orWhereIn('status', ['lunas', 'success']);
        })->sum('total_harga');
        
        $totalDibayar = (clone $groupedQuery)->where(function($q) {
            $q->where('is_paid', 1)->orWhereIn('status', ['lunas', 'success']);
        })->count();

        $transaksi = $groupedQuery->get();

        return view("admin.transaksi.index", compact("transaksi", "totalTransaksi", "totalPendapatan", "totalDibayar"));
    }

    /**
     * Export rekap transaksi ke CSV (Excel-compatible).
     */
    public function exportExcel(Request $request)
    {
        $query = Transaksi::with('produk')->latest();

        if ($request->filled('date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('tanggal_transaksi', $request->date)
                  ->orWhereDate('created_at', $request->date);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                  ->orWhere('id_transaksi', 'LIKE', "%{$search}%");
            });
        }

        $subquery = clone $query;
        $subquery->getQuery()->orders = null; // Remove orders for strict mode
        $subquery->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));
        $representativeIds = $subquery->selectRaw('MIN(id) as id')->pluck('id');

        $groupedQuery = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_msisdn')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with(['produk', 'supervisor', 'pelanggan'])
            ->latest();

        $transaksis = $groupedQuery->get();

        $filename = 'rekap-transaksi';
        if ($request->filled('date')) {
            $filename .= '-' . $request->date;
        }
        $filename .= '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($transaksis) {
            $handle = fopen('php://output', 'w');
            // Menambahkan BOM untuk UTF-8 dan instruksi separator agar Excel otomatis membagi kolom
            fwrite($handle, "\xEF\xBB\xBF");
            fwrite($handle, "sep=,\n");

            fputcsv($handle, [
                'No', 
                'ID Transaksi/Grup', 
                'Pelanggan / Travel', 
                'Info MSISDN', 
                'Nama Produk', 
                'Jumlah Beli (Pax)',
                'Total Bayar (Rp)', 
                'Tanggal Transaksi', 
                'Status Pembayaran', 
                'Metode Pembayaran',
                'Nama Sales'
            ]);

            foreach ($transaksis as $i => $t) {
                if ($t->status === 'lunas' || $t->status === 'success' || $t->is_paid) {
                    $status = 'LUNAS';
                } elseif ($t->status === 'pending' || !$t->status) {
                    $status = 'PENDING';
                } elseif ($t->status === 'batal') {
                    $status = 'DIBATALKAN';
                } else {
                    $status = strtoupper($t->status);
                }

                $isTravel = $t->supervisor && $t->supervisor->role === 'travel';
                $pelangganName = $isTravel ? ($t->supervisor->name . ' (B2B Travel)') : ($t->nama_pelanggan ?? '-');
                $namaSales = $isTravel ? '-' : ($t->nama_sales ?? '-');
                
                $msisdnInfo = $isTravel 
                    ? ($t->total_msisdn . ' Nomor MSISDN (Kolektif)')
                    : ($t->telepon_pelanggan ?: ($t->pelanggan->phone ?? '-'));
                
                $idGroup = $t->snap_token ?: $t->id_transaksi;

                fputcsv($handle, [
                    $i + 1,
                    $idGroup,
                    $pelangganName,
                    $msisdnInfo,
                    $t->produk?->produk_nama ?? '-',
                    $t->total_msisdn ?? 1,
                    $t->akumulasi_harga ?? $t->total_harga ?? 0,
                    $t->tanggal_transaksi ? Carbon::parse($t->tanggal_transaksi)->format('d/m/Y H:i') : '-',
                    $status,
                    strtoupper($t->metode_pembayaran ?? '-'),
                    $namaSales
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tampilkan detail transaksi (jika diperlukan via route show).
     */
    public function show($id)
    {
        $transaksi = Transaksi::with('produk')->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * Update nomor roaming secara manual via AJAX.
     */
    public function updateRoaming(Request $request, $id)
    {
        $request->validate([
            'nomor_roaming' => 'nullable|string|max:20|regex:/^[0-9+\- ]+$/',
        ], [
            'nomor_roaming.regex' => 'Format nomor roaming tidak valid. Gunakan angka, +, -, atau spasi.',
            'nomor_roaming.max'   => 'Nomor roaming maksimal 20 karakter.',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->nomor_roaming = $request->nomor_roaming;
            $transaksi->save();

            return response()->json([
                'success' => true,
                'message' => 'Nomor roaming berhasil diperbarui.',
                'nomor_roaming' => $transaksi->nomor_roaming
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nomor roaming: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Aktivasi paket oleh tim internal dengan mengunggah bukti injeksi.
     */
    public function aktivasi(Request $request, $id)
    {
        $request->validate([
            'bukti_injeksi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);

            if ($request->hasFile('bukti_injeksi')) {
                $file = $request->file('bukti_injeksi');
                $path = cloudinary()->upload($file->getRealPath(), ['folder' => 'bukti_injeksi'])->getSecurePath();
                
                $transaksi->bukti_injeksi = $path;
                $transaksi->is_activated = 1;
                $transaksi->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Paket berhasil diaktifkan dan bukti telah diunggah.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File bukti injeksi tidak ditemukan.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Monitor transaksi yang di-void (soft deleted) - tampilan Admin.
     */
    public function monitorVoid(Request $request)
    {
        $transaksi = Transaksi::onlyTrashed()
            ->with(['produk' => fn($q) => $q->withTrashed()])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $groupedTransaksi = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_transaksi)->format('Y-m-d');
        });

        $totalsPerDate = $groupedTransaksi->map(function ($items) {
            return [
                'totalPenjualan' => $items->sum(fn($i) => $i->produk ? $i->produk->produk_harga_akhir : 0),
                'totalInsentif'  => $items->sum(fn($i) => $i->produk ? $i->produk->produk_insentif : 0),
            ];
        });

        $totalPenjualan = $totalsPerDate->sum('totalPenjualan');
        $totalInsentif  = $totalsPerDate->sum('totalInsentif');

        return view('admin.monitor.void', compact('groupedTransaksi', 'totalsPerDate', 'totalPenjualan', 'totalInsentif'));
    }

    /**
     * Monitor setoran harian sales - tampilan Admin.
     */
    public function monitorSetoran(Request $request)
    {
        $transaksi = Transaksi::with(['produk', 'sales'])
            ->whereHas('sales', fn($q) => $q->where('role', 'sales'))
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $groupedData = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_transaksi)->format('Y-m-d');
        })->map(function ($dateItems) {
            return $dateItems->groupBy('nama_sales')->map(function ($salesItems) {
                return [
                    'total_sales'    => $salesItems->sum(fn($i) => $i->produk ? $i->produk->produk_harga_akhir : 0),
                    'total_insentif' => $salesItems->sum(fn($i) => $i->produk ? $i->produk->produk_insentif : 0),
                    'total_setor'    => $salesItems->where('is_setor', true)->sum(fn($i) => $i->produk ? $i->produk->produk_harga_akhir : 0),
                    'total_pending'  => $salesItems->where('is_setor', false)->sum(fn($i) => $i->produk ? $i->produk->produk_harga_akhir : 0),
                    'total_verified' => $salesItems->where('is_verified_setor', true)->sum(fn($i) => $i->produk ? $i->produk->produk_harga_akhir : 0),
                    'count'          => $salesItems->count(),
                    'is_all_setor'   => $salesItems->every('is_setor', true),
                    'is_all_verified'=> $salesItems->where('is_setor', true)->every('is_verified_setor', true) && $salesItems->where('is_setor', true)->count() > 0,
                ];
            });
        });

        return view('admin.monitor.setoran', compact('groupedData'));
    }

    /**
     * Approve setoran sales oleh admin.
     */
    public function approveSetoran(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'sales_name' => 'required|string'
        ]);

        try {
            $updated = Transaksi::whereDate('tanggal_transaksi', $request->date)
                ->where('nama_sales', $request->sales_name)
                ->where('is_setor', true)
                ->update(['is_verified_setor' => true]);

            if ($updated > 0) {
                return redirect()->back()->with('success', "Berhasil menyetujui setoran dari {$request->sales_name} untuk tanggal " . Carbon::parse($request->date)->format('d F Y'));
            } else {
                return redirect()->back()->with('info', "Tidak ada setoran yang perlu disetujui dari {$request->sales_name} pada tanggal tersebut.");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui setoran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus permanen transaksi yang di-void.
     */
    public function destroyVoid($id)
    {
        try {
            $transaksi = Transaksi::withTrashed()->findOrFail($id);
            $transaksi->forceDelete();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
