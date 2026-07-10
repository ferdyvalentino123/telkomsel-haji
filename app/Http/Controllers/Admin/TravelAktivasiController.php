<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TravelAktivasiController extends Controller
{
    /**
     * Tampilkan daftar transaksi travel yang sudah lunas dan menunggu injeksi.
     */
    public function index(Request $request)
    {
        // Dapatkan user dengan role travel
        $travelUserIds = \App\Models\RoleUsers::where('role', 'travel')->pluck('id');

        $query = Transaksi::with('produk', 'supervisor')
            ->whereIn('id_supervisor', $travelUserIds)
            ->where(function ($q) {
                $q->where('is_paid', true)
                  ->orWhereIn('status', ['completed', 'lunas', 'success']);
            })
            ->orderByRaw('is_activated ASC')
            ->orderBy('created_at', 'asc');

        // Filter status aktivasi
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_activated', false);
            } elseif ($request->status === 'done') {
                $query->where('is_activated', true);
            }
        }

        // Filter by travel (id_supervisor)
        if ($request->filled('travel_id')) {
            $query->where('id_supervisor', $request->travel_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_telepon', 'LIKE', "%{$search}%")
                  ->orWhere('id_transaksi', 'LIKE', "%{$search}%")
                  ->orWhere('snap_token', 'LIKE', "%{$search}%");
            });
        }

        // Kelompokkan berdasarkan grup (snap_token atau prefix id_transaksi)
        // Ambil wakil per grup (MIN id)
        $subquery = Transaksi::select(DB::raw('MIN(id) as id'))
            ->whereIn('id_supervisor', $travelUserIds)
            ->where(function ($q) {
                $q->where('is_paid', true)
                  ->orWhereIn('status', ['completed', 'lunas', 'success']);
            });

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $subquery->where('is_activated', false);
            } elseif ($request->status === 'done') {
                $subquery->where('is_activated', true);
            }
        }

        if ($request->filled('travel_id')) {
            $subquery->where('id_supervisor', $request->travel_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $subquery->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_telepon', 'LIKE', "%{$search}%")
                  ->orWhere('id_transaksi', 'LIKE', "%{$search}%")
                  ->orWhere('snap_token', 'LIKE', "%{$search}%");
            });
        }

        $subquery->groupBy(DB::raw('COALESCE(snap_token, id_transaksi)'));
        $representativeIds = $subquery->pluck('id');

        $transaksis = Transaksi::whereIn('id', $representativeIds)
            ->select('transaksis.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as total_msisdn')
            ->selectRaw('(SELECT GROUP_CONCAT(t2.nomor_telepon ORDER BY t2.id SEPARATOR ",") FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as semua_msisdn')
            ->selectRaw('(SELECT SUM(t2.total_harga) FROM transaksis t2 WHERE t2.deleted_at IS NULL AND COALESCE(t2.snap_token, t2.id_transaksi) = COALESCE(transaksis.snap_token, transaksis.id_transaksi)) as akumulasi_harga')
            ->with(['produk', 'supervisor'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalPending = Transaksi::whereIn('id_supervisor', $travelUserIds)
            ->where(function ($q) {
                $q->where('is_paid', true)->orWhereIn('status', ['completed', 'lunas', 'success']);
            })
            ->where('is_activated', false)
            ->count(DB::raw('DISTINCT COALESCE(snap_token, id_transaksi)'));

        $totalDone = Transaksi::whereIn('id_supervisor', $travelUserIds)
            ->where(function ($q) {
                $q->where('is_paid', true)->orWhereIn('status', ['completed', 'lunas', 'success']);
            })
            ->where('is_activated', true)
            ->count(DB::raw('DISTINCT COALESCE(snap_token, id_transaksi)'));

        // Daftar travel untuk filter
        $travelList = \App\Models\RoleUsers::where('role', 'travel')->get();

        return view('admin.travel-aktivasi.index', compact(
            'transaksis', 'totalPending', 'totalDone', 'travelList'
        ));
    }

    /**
     * Upload bukti injeksi oleh Admin untuk transaksi travel.
     * Upload satu bukti untuk satu grup transaksi (semua MSISDN dalam 1 checkout).
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_injeksi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
            'catatan_injeksi' => 'nullable|string|max:500',
        ], [
            'bukti_injeksi.required' => 'File bukti injeksi wajib diupload.',
            'bukti_injeksi.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_injeksi.max'      => 'Ukuran file maksimal 8MB.',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);

            // Simpan file bukti ke Cloudinary
            $file = $request->file('bukti_injeksi');
            $path = cloudinary()->upload($file->getRealPath(), ['folder' => 'bukti_injeksi/travel'])->getSecurePath();
            $buktiUrl = $path; // URL Cloudinary

            // Tentukan grup yang akan diupdate (semua MSISDN dalam 1 sesi checkout)
            $snapToken = $transaksi->snap_token;
            $adminName = Auth::user()->name;
            $now = Carbon::now();
            $catatan = $request->input('catatan_injeksi', '');

            if ($snapToken) {
                // Update semua transaksi dalam 1 snap_token (1 sesi pembayaran)
                Transaksi::where('snap_token', $snapToken)->update([
                    'bukti_injeksi'   => $path,
                    'is_activated'    => true,
                    'injeksi_oleh'    => $adminName,
                    'injeksi_at'      => $now,
                    'catatan_injeksi' => $catatan,
                ]);
            } else {
                // Fallback: jika tidak ada snap_token, cek prefix id_transaksi
                $idTransaksi = $transaksi->id_transaksi;
                if (str_starts_with($idTransaksi, 'TRV-KOL-')) {
                    $parts = explode('-', $idTransaksi);
                    array_pop($parts);
                    $groupId = implode('-', $parts);
                    Transaksi::where('id_transaksi', 'like', $groupId . '%')->update([
                        'bukti_injeksi'   => $path,
                        'is_activated'    => true,
                        'injeksi_oleh'    => $adminName,
                        'injeksi_at'      => $now,
                        'catatan_injeksi' => $catatan,
                    ]);
                } else {
                    $transaksi->update([
                        'bukti_injeksi'   => $path,
                        'is_activated'    => true,
                        'injeksi_oleh'    => $adminName,
                        'injeksi_at'      => $now,
                        'catatan_injeksi' => $catatan,
                    ]);
                }
            }

            // Hitung jumlah MSISDN yang berhasil diinjeksi
            $jumlahMsisdn = $snapToken
                ? Transaksi::where('snap_token', $snapToken)->count()
                : 1;

            return response()->json([
                'success'       => true,
                'message'       => "Bukti injeksi berhasil diupload. {$jumlahMsisdn} nomor MSISDN telah ditandai aktif.",
                'bukti_url'     => $buktiUrl,
                'injeksi_oleh'  => $adminName,
                'injeksi_at'    => $now->translatedFormat('d M Y H:i'),
                'jumlah_msisdn' => $jumlahMsisdn,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload: ' . $e->getMessage(),
            ], 500);
        }
    }
}
