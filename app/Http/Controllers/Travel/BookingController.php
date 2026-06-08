<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Transaksi::where('id_supervisor', Auth::user()->id)
            ->where('status', 'booked')
            ->latest()
            ->paginate(10);
            
        $produks = Produk::where('produk_stok', '>', 0)->get();
            
        return view('travel.booking.index', compact('bookings', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'aktivasi_tanggal' => 'required|date',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        $idTransaksi = 'BKG-' . strtoupper(uniqid());

        Transaksi::create([
            'id_transaksi' => $idTransaksi,
            'nomor_telepon' => $request->nomor_telepon,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_transaksi' => Carbon::now(),
            'aktivasi_tanggal' => $request->aktivasi_tanggal,
            'nama_sales' => Auth::user()->name,
            'produk_id' => $produk->id,
            'jenis_paket' => $produk->id,
            'total_harga' => $produk->produk_harga_akhir,
            'metode_pembayaran' => 'Pending',
            'is_paid' => false,
            'status' => 'booked',
            'id_supervisor' => Auth::user()->id,
        ]);

        // Note: Booking usually doesn't decrement stock until it's paid, 
        // but it depends on the business logic. For now, we'll just record it.

        return redirect()->route('travel.booking.index')->with('success', 'Booking berhasil dicatat.');
    }
}
