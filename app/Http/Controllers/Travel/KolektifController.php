<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\RoleUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class KolektifController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $travelId = Auth::id();

        // Tampilkan HANYA produk milik travel ini
        $produks = Produk::where('produk_stok', '>', 0)
            ->where('travel_id', $travelId)
            ->get();

        return view('travel.kolektif.index', compact('produks'));
    }

    /**
     * Prefix Telkomsel yang valid.
     */
    private const TSEL_PREFIXES = [
        '0811','0812','0813','0821','0822','0823',
        '0851','0852','0853','0828','0838',
    ];

    /**
     * Normalisasi MSISDN: +62/62 → 0, hapus non-digit.
     */
    private function normalizeMsisdn(string $value): string
    {
        $v = trim($value);
        $v = preg_replace('/[^\d+]/', '', $v);
        if (str_starts_with($v, '+62')) {
            $v = '0' . substr($v, 3);
        } elseif (str_starts_with($v, '62')) {
            $v = '0' . substr($v, 2);
        }
        return $v;
    }

    /**
     * Cek apakah nomor menggunakan prefix Telkomsel.
     */
    private function isTselPrefix(string $number): bool
    {
        foreach (self::TSEL_PREFIXES as $prefix) {
            if (str_starts_with($number, $prefix)) return true;
        }
        return false;
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'msisdns'   => 'required|array|min:1',
            'msisdns.*' => 'required|string',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        // Normalisasi & filter kosong
        $msisdns = array_values(array_filter(
            array_map(fn($m) => $this->normalizeMsisdn($m), $request->msisdns)
        ));

        // --- Validasi manual per nomor ---
        $errors = [];
        $seen   = [];

        foreach ($msisdns as $i => $msisdn) {
            $label = 'Nomor ' . ($i + 1) . ' (' . $msisdn . ')';

            // Panjang digit 10–13
            $len = strlen($msisdn);
            if ($len < 10 || $len > 13) {
                $errors[] = "{$label}: panjang harus 10–13 digit (saat ini {$len} digit).";
                continue;
            }

            // Prefix Telkomsel
            if (!$this->isTselPrefix($msisdn)) {
                $errors[] = "{$label}: bukan nomor Telkomsel. Prefix yang diizinkan: 0811, 0812, 0813, 0821, 0822, 0823, 0851, 0852, 0853, dll.";
                continue;
            }

            // Duplikat dalam satu transaksi
            if (in_array($msisdn, $seen)) {
                $errors[] = "{$label}: nomor ini duplikat. Setiap nomor harus unik dalam satu transaksi.";
                continue;
            }

            $seen[] = $msisdn;
        }

        if (!empty($errors)) {
            return back()
                ->with('error', implode('<br>', $errors))
                ->withInput();
        }

        // Gunakan hanya nomor yang sudah lolos validasi
        $msisdns = $seen;
        $count   = count($msisdns);

        if ($produk->produk_stok < $count) {
            return back()->with('error', "Stok tidak mencukupi untuk {$count} MSISDN. Stok tersedia: {$produk->produk_stok}")->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate a unique Group ID for Midtrans Order
            $groupId = 'TRV-KOL-' . time() . '-' . rand(1000, 9999);
            $totalHarga = $produk->produk_harga_akhir * $count;
            $firstTransaksiId = null;

            foreach ($msisdns as $index => $msisdn) {
                $idTransaksi = $groupId . '-' . $index;

                $transaksi = Transaksi::create([
                    'id_transaksi' => $idTransaksi,
                    'nomor_telepon' => $msisdn,
                    'nama_pelanggan' => 'Pelanggan Kolektif (' . Auth::user()->name . ')',
                    'tanggal_transaksi' => Carbon::now(),
                    'nama_sales' => Auth::user()->name,
                    'produk_id' => $produk->id,
                    'jenis_paket' => $produk->id,
                    'total_harga' => $produk->produk_harga_akhir,
                    'metode_pembayaran' => 'Midtrans / QRIS',
                    'is_paid' => false,
                    'status' => 'pending',
                    'id_supervisor' => Auth::user()->id, // Link to the travel agent
                ]);

                if (!$firstTransaksiId) {
                    $firstTransaksiId = $transaksi->id;
                }

                // Decrement stock
                $produk->decrement('produk_stok', 1);
                $produk->increment('produk_terjual', 1);
            }

            // Request Snap Token from Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $groupId,
                    'gross_amount' => (int) $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email ?? 'travel@test.com',
                    'phone' => Auth::user()->phone ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id' => $produk->id,
                        'price' => (int) $produk->produk_harga_akhir,
                        'quantity' => $count,
                        'name' => 'Kolektif: ' . $produk->produk_nama,
                    ]
                ],
                'callbacks' => [
                    'finish' => route('travel.transaksi.index'),
                    'unfinish' => route('travel.transaksi.index'),
                    'error' => route('travel.transaksi.index')
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update snap token in all these transactions
            Transaksi::where('id_transaksi', 'like', $groupId . '%')
                ->update(['snap_token' => $snapToken]);

            DB::commit();

            return redirect()->route('travel.kolektif.pembayaran', $firstTransaksiId)
                ->with('success', "Transaksi kolektif berhasil dibuat. Silakan lakukan pembayaran.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function pembayaran($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('id_supervisor', Auth::id())
            ->with('produk')
            ->firstOrFail();

        $grupTransaksi = Transaksi::where('snap_token', $transaksi->snap_token)
            ->with('produk')
            ->get();
            
        $totalHarga = $grupTransaksi->sum('total_harga');

        return view('travel.kolektif.pembayaran', compact('transaksi', 'grupTransaksi', 'totalHarga'));
    }
}
