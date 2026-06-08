<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DashboardController;

Route::middleware(['auth', 'sales'])->group(function () {
    Route::get('/programhaji/sales', [DashboardController::class, 'index'])->name('sales.home');
    
    // Transaksi Sales
    Route::get('/programhaji/sales/transaksi', [DashboardController::class, 'transaksiPage'])->name('sales.transaksi');
    Route::post('/programhaji/sales/transaksi/submit', [\App\Http\Controllers\Kasir\TransaksiController::class, 'submit'])->name('sales/transaksi/submit');
    
    // Pembayaran Midtrans Sales
    Route::get('/programhaji/sales/pembayaran/{id}', [\App\Http\Controllers\Kasir\TransaksiController::class, 'pembayaran'])->name('sales.pembayaran');
    Route::get('/programhaji/sales/pembayaran/{id}/callback', [\App\Http\Controllers\Kasir\TransaksiController::class, 'callbackPembayaran'])->name('sales.pembayaran.callback');
    Route::post('/programhaji/sales/pembayaran/{id}/cancel', [\App\Http\Controllers\Kasir\TransaksiController::class, 'cancelPembayaran'])->name('sales.pembayaran.cancel');
    
    // Rekap/Pendapatan Sales
    Route::get('/programhaji/sales/rekap', [\App\Http\Controllers\Kasir\TransaksiController::class, 'dashboard'])->name('sales/rekap');
    
    // Toggle Aktivasi (digunakan di halaman rekap)
    Route::post('/programhaji/transaksi/{id}/toggle-activate', [DashboardController::class, 'toggleActivate'])->name('transaksi.toggle-activate');

    // Cetak Nota (digunakan di halaman rekap)
    Route::get('/programhaji/sales/transaksi/print/{id}', [\App\Http\Controllers\Kasir\TransaksiController::class, 'print'])->name('sales.transaksi.print');
    // Toggle Void (Hapus/Restore)
    Route::post('/programhaji/transaksi/{id}/toggle-void', [\App\Http\Controllers\Kasir\TransaksiController::class, 'toggleVoid'])->name('transaksi.toggle-void');

    // Setor Transaksi
    Route::post('/programhaji/sales/transaksi/setor', [\App\Http\Controllers\Kasir\TransaksiController::class, 'setor'])->name('sales.transaksi.setor');

    // Kelola Aktivasi Pelanggan Online
    Route::get('/programhaji/sales/aktivasi', [\App\Http\Controllers\Sales\AktivasiController::class, 'index'])->name('sales.aktivasi.index');
    Route::post('/programhaji/sales/aktivasi/{id}/upload', [\App\Http\Controllers\Sales\AktivasiController::class, 'upload'])->name('sales.aktivasi.upload');
});