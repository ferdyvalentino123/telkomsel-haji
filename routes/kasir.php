<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Kasir\ExportApprovedTransaksiController;
use App\Http\Controllers\Kasir\ExportRiwayatTransaksiController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\ProdukController;
use App\Http\Controllers\Kasir\MerchandiseController;
use App\Http\Controllers\Kasir\StockHistoryController;
use App\Http\Controllers\Kasir\BudgetInsentifController;

Route::middleware(["auth", "kasir"])->group(function () {
    Route::get("/programhaji/kasir", [DashboardController::class, "index"])->name("kasir.home");
    Route::post("/programhaji/kasir/export-approved", [ExportApprovedTransaksiController::class, "export"])->name("kasir.export-approved");
    Route::post("/programhaji/kasir/export-riwayat", [ExportRiwayatTransaksiController::class, "export"])->name("kasir.export-riwayat");
    Route::get("/programhaji/kasir/export-excel", [ExportRiwayatTransaksiController::class, "exportExcel"])->name("kasir.export.excel");
    Route::get("/programhaji/kasir/approvetransaksi", [TransaksiController::class, "approveTransaksi"])->name("kasir.transaksi.approve");
    Route::get("/programhaji/kasir/approvetransaksi/refresh", [TransaksiController::class, "refresh"])->name("kasir.transaksi.refresh");
    Route::post("/programhaji/kasir/approvetransaksi/bulk-approve", [TransaksiController::class, "bulkApprove"])->name("kasir.transaksi.bulkApprove");
    
    Route::get("/programhaji/kasir/transaksi/{id}/bayar", [TransaksiController::class, "editBayar"])->name("kasir.transaksi.bayar.edit");
    Route::put("/programhaji/kasir/transaksi/{id}/bayar", [TransaksiController::class, "bayar"])->name("kasir.transaksi.bayar.update");
    Route::get("/programhaji/kasir/transaksi/{id}/edit", [TransaksiController::class, "edit"])->name("kasir.transaksi.edit");
    Route::put("/programhaji/kasir/transaksi/{id}/update", [TransaksiController::class, "update"])->name("kasir.transaksi.update");
    Route::delete("/programhaji/kasir/transaksi/{id}/forcedelete", [TransaksiController::class, "forcedelete"])->name("kasir.transaksi.forcedelete");
    Route::put("/programhaji/kasir/transaksi/kwitansi/unlunas/{id}", [TransaksiController::class, "unlunas"])->name("kasir.transaksi.unlunas");
    Route::get("/programhaji/kasir/transaksi/kwitansi/print/{id}", [TransaksiController::class, "print"])->name("kasir.transaksi.print");
    
    Route::get("/programhaji/kasir/add-sales", [DashboardController::class, "create"])->name("kasir.add_sales");
    Route::post("/programhaji/kasir/store-sales", [DashboardController::class, "store"])->name("kasir.store_sales");
    
    Route::get("/programhaji/kasir/produk", [ProdukController::class, "index"])->name("kasir.produk.index");
    Route::get("/programhaji/kasir/produk/{produk}", [ProdukController::class, "show"])->name("kasir.produk.show");
    
    Route::get("/programhaji/kasir/merchandise", [MerchandiseController::class, "index"])->name("kasir.merchandise.index");
    Route::get("/programhaji/kasir/merchandise/{merchandise}", [MerchandiseController::class, "show"])->name("kasir.merchandise.show");
    
    Route::get("/programhaji/kasir/stock-history", [StockHistoryController::class, "index"])->name("kasir.stock-history.index");
    Route::get("/programhaji/kasir/stock-history/{stockHistory}", [StockHistoryController::class, "show"])->name("kasir.stock-history.show");
    
    Route::get("/programhaji/kasir/history-setoran", [DashboardController::class, "showHistorySetoran"])->name("kasir.history-setoran");
    
    Route::post("/programhaji/kasir/store", [DashboardController::class, "store"])->name("kasir.store");
    Route::post("/programhaji/kasir/update-setoran-sales", [DashboardController::class, "updateSetoranSales"])->name("kasir.update.setoran.sales");
    Route::post("/programhaji/kasir/update-setoran-status", [DashboardController::class, "updateSetoranStatus"])->name("kasir.update.setoran.status");
    
    Route::get("/programhaji/kasir/monitor/void", [TransaksiController::class, "voidMonitor"])->name("kasir.monitor.void");
    
    Route::get("/programhaji/kasir/monitor/setoran", [TransaksiController::class, "monitorSetoran"])->name("kasir.monitor.setoran");
    
    Route::get("/programhaji/kasir/transaksi", [TransaksiController::class, "index"])->name("kasir.transaksi.index");
    
    Route::get("/programhaji/kasir/budget-insentif", [BudgetInsentifController::class, "index"])->name("kasir.budget_insentif.index");
    Route::get("/programhaji/kasir/budget-insentif/pantau", [BudgetInsentifController::class, "pantau"])->name("kasir.budget_insentif.pantau");
    Route::post("/programhaji/kasir/budget-insentif/update", [BudgetInsentifController::class, "update"])->name("kasir.budget_insentif.update");
    
    Route::prefix("transactions")->name("kasir.transactions.")->group(function () {
        Route::get("/", [TransaksiController::class, "index"])->name("index");
    });
});
