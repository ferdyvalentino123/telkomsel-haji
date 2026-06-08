<?php
namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Total Products (Filtered for this Travel Agent)
        $totalProducts = Produk::where('travel_id', $userId)->count();
        
        // Total Transactions (Filtered for this Travel Agent - count of all individual registered MSISDNs)
        $statsQuery = Transaksi::where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            });
        $totalTransactions = (clone $statsQuery)->count();
        
        // Total Revenue (Filtered)
        $totalRevenue = (clone $statsQuery)->sum('total_harga') ?? 0;
        
        // Recent Products (Last 5)
        $recentProducts = Produk::where('travel_id', $userId)->latest()->limit(5)->get();
        
        // Featured Products (Top 3 from this Travel Agent)
        $featuredProducts = Produk::where('travel_id', $userId)->limit(3)->get();
        
        // Get representative IDs for recent transactions (last 10 grouped checkouts)
        $subquery = Transaksi::select(DB::raw('MIN(id) as id'))
            ->where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));

        $representativeIds = $subquery->pluck('id');

        // Recent Transactions (Last 10 - Filtered & Grouped)
        $recentTransactions = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_jumlah')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with('produk')
            ->latest()
            ->limit(10)
            ->get();
        
        // Completed Transactions (This Month - Filtered)
        $monthlyTransactions = Transaksi::where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->count();
        
        // Monthly Revenue (Filtered)
        $monthlyRevenue = Transaksi::where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('total_harga') ?? 0;
        
        // Low Stock Products (Filtered for this Travel Agent)
        $lowStockProducts = Produk::where('travel_id', $userId)->whereRaw('produk_stok <= 5')->count();
        
        // Out of Stock Products (Filtered for this Travel Agent)
        $outOfStockProducts = Produk::where('travel_id', $userId)->where('produk_stok', 0)->count();
        
        // Uninjected Transactions (Belum diinjeksi)
        $subqueryUninjected = Transaksi::select(DB::raw('MIN(id) as id'))
            ->where('id_supervisor', $userId)
            ->whereHas('produk', function($q) use ($userId) {
                $q->where('travel_id', $userId);
            })
            ->where(function($q) {
                $q->whereNull('is_activated')->orWhere('is_activated', 0);
            })
            ->whereNotIn('status', ['cancelled', 'batal', 'gagal', 'failed'])
            ->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));

        $uninjectedIds = $subqueryUninjected->pluck('id');

        $uninjectedTransactions = Transaksi::whereIn('id', $uninjectedIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_jumlah')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND t2.id_supervisor = transaksis.id_supervisor AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with('produk')
            ->latest()
            ->limit(5)
            ->get();
        
        return view('travel.dashboard.index', [
            'totalProducts' => $totalProducts,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'recentProducts' => $recentProducts,
            'featuredProducts' => $featuredProducts,
            'recentTransactions' => $recentTransactions,
            'monthlyTransactions' => $monthlyTransactions,
            'monthlyRevenue' => $monthlyRevenue,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'uninjectedTransactions' => $uninjectedTransactions,
        ]);
    }
}

