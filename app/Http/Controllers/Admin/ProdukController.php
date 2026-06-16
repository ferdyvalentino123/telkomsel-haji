<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produk;
use App\Models\RoleUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $produk = Produk::with('travel')
            ->when($search, function ($query, $search) {
                $query->where('produk_nama', 'like', "%{$search}%")
                      ->orWhere('produk_detail', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view("admin.produk.index", compact("produk"));
    }

    public function create()
    {
        $travels = RoleUsers::where('role', 'travel')->orderBy('name')->get();
        return view("admin.produk.form", compact('travels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "produk_nama"    => "required|string|max:255",
            "produk_harga"   => "required|numeric|min:0",
            "produk_stok"    => "required|integer|min:0",
            "produk_detail"  => "nullable|string",
            "kuota"          => "nullable|string",
            "masa_aktif"     => "nullable|string",
            "produk_insentif" => "nullable|numeric|min:0",
            "travel_id"      => "nullable|exists:role_users,id",
        ]);

        $produk = Produk::create($validated);

        // Record History
        \App\Models\StockHistory::create([
            'product_id' => $produk->id,
            'change_amount' => $produk->produk_stok,
            'previous_stock' => 0,
            'current_stock' => $produk->produk_stok,
            'action' => 'Tambah (Initial)',
        ]);

        return redirect("/programhaji/admin/produk")->with("success", "Produk berhasil ditambahkan");
    }

    public function show(Produk $produk)
    {
        return view("admin.produk.show", compact("produk"));
    }

    public function edit(Produk $produk)
    {
        $travels = RoleUsers::where('role', 'travel')->orderBy('name')->get();
        return view("admin.produk.form", compact('produk', 'travels'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            "produk_nama"    => "required|string|max:255",
            "produk_harga"   => "required|numeric|min:0",
            "produk_stok"    => "required|integer|min:0",
            "produk_detail"  => "nullable|string",
            "kuota"          => "nullable|string",
            "masa_aktif"     => "nullable|string",
            "produk_insentif" => "nullable|numeric|min:0",
            "travel_id"      => "nullable|exists:role_users,id",
        ]);

        $oldStock = $produk->produk_stok;
        $newStock = $validated['produk_stok'];

        $produk->update($validated);

        if ($oldStock != $newStock) {
            \App\Models\StockHistory::create([
                'product_id' => $produk->id,
                'change_amount' => abs($newStock - $oldStock),
                'previous_stock' => $oldStock,
                'current_stock' => $newStock,
                'action' => $newStock > $oldStock ? 'Tambah (Update)' : 'Kurang (Update)',
            ]);
        }

        return redirect("/programhaji/admin/produk")->with("success", "Produk berhasil diperbarui");
    }

    public function destroy(Produk $produk)
    {
        try {
            $produk->delete();
            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()]);
        }
    }
}
