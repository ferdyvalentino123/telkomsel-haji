<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class KonfirmasiController extends Controller
{
    /**
     * Tampilkan Surat Konfirmasi Injeksi yang bisa dicetak/diunduh.
     */
    public function show($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('id_supervisor', Auth::id())
            ->with(['produk', 'supervisor'])
            ->firstOrFail();

        // Dapatkan semua transaksi dalam satu grup
        $snapToken = $transaksi->snap_token;
        if ($snapToken) {
            $grupTransaksi = Transaksi::where('snap_token', $snapToken)
                ->where('id_supervisor', Auth::id())
                ->with('produk')
                ->get();
        } else {
            $idTransaksi = $transaksi->id_transaksi;
            if (str_starts_with($idTransaksi, 'TRV-KOL-')) {
                $parts = explode('-', $idTransaksi);
                array_pop($parts);
                $gId = implode('-', $parts);
                $grupTransaksi = Transaksi::where('id_transaksi', 'like', $gId . '%')
                    ->where('id_supervisor', Auth::id())
                    ->with('produk')
                    ->get();
            } else {
                $grupTransaksi = collect([$transaksi]);
            }
        }

        $firstActivated = $grupTransaksi->first(fn($t) => $t->is_activated && $t->bukti_injeksi);

        if (!$firstActivated) {
            abort(403, 'Bukti injeksi belum tersedia. Silakan tunggu proses dari admin.');
        }

        $totalHarga = $grupTransaksi->sum('total_harga');

        return view('travel.transaksi.konfirmasi', compact(
            'transaksi', 'grupTransaksi', 'firstActivated', 'totalHarga'
        ));
    }
}
