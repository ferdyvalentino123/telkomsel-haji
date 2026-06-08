<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;

        // Filter by date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Stats query over all individual records (to show true total registered numbers and revenue)
        $statsQuery = Transaksi::where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->whereBetween('tanggal_transaksi', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        $totalRevenue = (clone $statsQuery)->sum('total_harga');
        $completedCount = (clone $statsQuery)->where('status', 'completed')->count();
        $bookedCount = (clone $statsQuery)->where('status', 'booked')->count();

        // Get representative IDs for the date range
        $subquery = Transaksi::select(DB::raw('MIN(id) as id'))
            ->where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->whereBetween('tanggal_transaksi', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));

        $representativeIds = $subquery->pluck('id');

        // Total checkout transactions in the period
        $totalTransactions = count($representativeIds);

        // Fetch representative transaction records with total counts and accumulated prices
        $transactions = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_jumlah')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with('produk')
            ->latest()
            ->paginate(20);

        return view('travel.laporan.index', compact(
            'transactions', 
            'totalTransactions', 
            'totalRevenue', 
            'completedCount', 
            'bookedCount',
            'startDate',
            'endDate'
        ));
    }
}
