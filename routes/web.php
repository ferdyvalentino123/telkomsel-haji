<?php

use Illuminate\Support\Facades\Route;

// Root landing page
Route::get('/', function () {
    return view('welcome');
});

// Programhaji landing page redirect
Route::get('/programhaji/', function () {
    return redirect('/programhaji/login');
});

// ────────────────────────────────────────────────────────────
// IMPORT ALL ROUTE FILES
// ────────────────────────────────────────────────────────────

// Auth Routes (login, register, OAuth)
require __DIR__ . '/auth.php';

// Unified Profile Routes (for sales, supervisor, etc.)
Route::middleware(['auth'])->group(function () {
    Route::get('/programhaji/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('role_users.edit');
    Route::put('/programhaji/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('role_users.update');
});

// Admin Routes (auth + role:admin)
require __DIR__ . '/admin.php';


// Kasir Routes (auth + role:kasir)
require __DIR__ . '/kasir.php';

// Sales Routes (auth + role:sales)
require __DIR__ . '/sales.php';

// Pelanggan Routes (auth + role:pelanggan)
require __DIR__ . '/pelanggan.php';

// Travel Routes (auth + role:travel)
require __DIR__ . '/travel.php';

// Add this to routes/web.php temporarily
Route::get('/create-admin', function () {
    $admin = \App\Models\RoleUsers::firstOrCreate(
        ['email' => 'admin@haji.com'],
        [
            'name' => 'Administrator',
            'pin' => '123456',
            'role' => 'admin',
            'phone' => '08111111111',
            'is_superuser' => true,
        ]
    );
    
    return "<h3>Akun Admin Berhasil Dibuat/Ditemukan!</h3>" .
           "<p>Email: <b>{$admin->email}</b></p>" .
           "<p>PIN: <b>123456</b></p>" .
           "<p>Role: <b>{$admin->role}</b></p>" .
           "<br><a href='/programhaji/login'>Kembali ke Login</a>";
});