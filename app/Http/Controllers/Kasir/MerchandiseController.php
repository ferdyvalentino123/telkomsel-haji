<?php
namespace App\Http\Controllers\Kasir;
use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use Illuminate\Http\Request;
class MerchandiseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input("search");
        $merchandise = Merchandise::when($search, function($query, $search) {
            $query->where("merch_nama", "like", "%{$search}%")->orWhere("merch_detail", "like", "%{$search}%");
        })->latest()->paginate(15)->withQueryString();
        return view("kasir.merchandise.index", compact("merchandise", "search"));
    }
    public function show(Merchandise $merchandise)
    {
        return view("kasir.merchandise.show", compact("merchandise"));
    }
}
