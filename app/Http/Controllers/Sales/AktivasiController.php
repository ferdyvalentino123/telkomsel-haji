<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivasiController extends Controller
{
    /**
     * Tampilkan daftar transaksi pelanggan online yang perlu diaktivasi.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with('produk')
            ->whereNotNull('id_pelanggan')   // hanya transaksi online
            ->whereIn('status', ['lunas', 'success'])
            ->orderByRaw('is_activated ASC') // belum aktif dulu
            ->orderBy('aktivasi_tanggal', 'asc');

        // Filter by status aktivasi
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_activated', false);
            } elseif ($request->status === 'done') {
                $query->where('is_activated', true);
            }
        }

        // Filter by tanggal aktivasi
        if ($request->filled('tanggal')) {
            $query->whereDate('aktivasi_tanggal', $request->tanggal);
        }

        // Search by name/phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('telepon_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('id_transaksi', 'LIKE', "%{$search}%");
            });
        }

        $transaksis = $query->get();

        $totalPending  = $transaksis->where('is_activated', false)->count();
        $totalDone     = $transaksis->where('is_activated', true)->count();

        return view('sales.aktivasi', compact('transaksis', 'totalPending', 'totalDone'));
    }

    /**
     * Upload bukti injeksi oleh Sales.
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_injeksi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ], [
            'bukti_injeksi.required' => 'File bukti injeksi wajib diupload.',
            'bukti_injeksi.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_injeksi.max'      => 'Ukuran file maksimal 4MB.',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);

            $file = $request->file('bukti_injeksi');
            if (!$file || !$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File bukti injeksi tidak valid atau tidak diterima server.'
                ], 400);
            }
            
            $path = cloudinary()->uploadApi()->upload($file->getRealPath(), ['folder' => 'bukti_injeksi'])['secure_url'];

            $transaksi->bukti_injeksi = $path;
            $transaksi->is_activated  = 1;
            $transaksi->save();

            // Generate WhatsApp link
            $buktiUrl  = $path; // URL langsung dari Cloudinary
            $telepon   = preg_replace('/[^0-9]/', '', $transaksi->telepon_pelanggan ?? '');
            if (str_starts_with($telepon, '0')) {
                $telepon = '62' . substr($telepon, 1);
            }
            $namaPaket = $transaksi->produk->produk_nama ?? 'Paket RoaMAX';
            $pesan = "Halo {$transaksi->nama_pelanggan}! 👋\n\nPaket *{$namaPaket}* Anda telah berhasil diaktifkan. ✅\n\nBerikut bukti injeksi paket Anda:\n{$buktiUrl}\n\nTerima kasih telah menggunakan layanan kami. 🙏";
            $waLink = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

            return response()->json([
                'success'   => true,
                'message'   => 'Bukti injeksi berhasil diupload. Paket telah ditandai aktif.',
                'bukti_url' => $buktiUrl,
                'wa_link'   => $waLink,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload: ' . $e->getMessage() . ' di baris ' . $e->getLine(),
            ], 500);
        }
    }
}
