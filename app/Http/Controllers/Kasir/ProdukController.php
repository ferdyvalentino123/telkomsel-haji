<?php
namespace App\Http\Controllers\Kasir;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input("search");
        $produk = Produk::when($search, function($query, $search) {
            $query->where("produk_nama", "like", "%{$search}%")->orWhere("produk_detail", "like", "%{$search}%");
        })->latest()->paginate(15)->withQueryString();
        return view("kasir.produk.index", compact("produk", "search"));
    }
    public function show(Produk $produk)
    {
        return view("kasir.produk.show", compact("produk"));
    }
}
