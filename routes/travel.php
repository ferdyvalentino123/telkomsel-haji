<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Travel\DashboardController;
use App\Http\Controllers\Travel\ProdukController;
use App\Http\Controllers\Travel\TransaksiController;
use App\Http\Controllers\Travel\KonfirmasiController;

Route::middleware(['auth'])->group(function () {
    Route::get('/programhaji/travel', [DashboardController::class, 'index'])->name('travel.home');
    
    Route::get('/programhaji/travel/transaksi', [TransaksiController::class, 'index'])->name('travel.transaksi.index');
    Route::get('/programhaji/travel/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('travel.transaksi.show');
    Route::get('/programhaji/travel/transaksi/{id}/konfirmasi', [KonfirmasiController::class, 'show'])->name('travel.transaksi.konfirmasi');

    // Pembelian Kolektif
    Route::get('/programhaji/travel/kolektif', [\App\Http\Controllers\Travel\KolektifController::class, 'index'])->name('travel.kolektif.index');
    Route::post('/programhaji/travel/kolektif', [\App\Http\Controllers\Travel\KolektifController::class, 'store'])->name('travel.kolektif.store');
    Route::get('/programhaji/travel/kolektif/pembayaran/{id}', [\App\Http\Controllers\Travel\KolektifController::class, 'pembayaran'])->name('travel.kolektif.pembayaran');

    // Laporan
    Route::get('/programhaji/travel/laporan', [\App\Http\Controllers\Travel\LaporanController::class, 'index'])->name('travel.laporan.index');

    // Profil
    Route::get('/programhaji/travel/profil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('travel.profil');
    Route::put('/programhaji/travel/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('travel.profil.update');
});
