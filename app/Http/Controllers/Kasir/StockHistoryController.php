<?php
namespace App\Http\Controllers\Kasir;
use App\Http\Controllers\Controller;
use App\Models\StockHistory;
use Illuminate\Http\Request;
class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input("search");
        $date = $request->input("date");
        $stockHistory = StockHistory::with(["product", "merchandise"])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas("product", function($pq) use ($search) {
                        $pq->where("produk_nama", "like", "%{$search}%");
                    })->orWhereHas("merchandise", function($mq) use ($search) {
                        $mq->where("merch_nama", "like", "%{$search}%");
                    })->orWhere("action", "like", "%{$search}%");
                });
            })
            ->when($date, function($query, $date) {
                $query->whereDate("created_at", $date);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view("kasir.stock-history.index", compact("stockHistory", "search", "date"));
    }

    public function show(StockHistory $stockHistory)
    {
        $stockHistory->load(["product", "merchandise"]);
        return view("kasir.stock-history.show", compact("stockHistory"));
    }
}
