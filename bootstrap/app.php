<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => App\Http\Middleware\CheckRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin' => App\Http\Middleware\EnsureAdmin::class,
            'supervisor' => App\Http\Middleware\EnsureSupervisor::class,
            'kasir' => App\Http\Middleware\EnsureKasir::class,
            'sales' => App\Http\Middleware\EnsureSales::class,
            'pelanggan' => App\Http\Middleware\PelangganMiddleware::class,
            'complete_profile' => App\Http\Middleware\EnsureProfileCompleted::class,
        ]);

        // Hapus ValidatePostSize karena Vercel sudah membatasi di Edge network, 
        // dan default post_max_size dari vercel-php sangat kecil sehingga menyebabkan PostTooLargeException
        $middleware->remove(\Illuminate\Http\Middleware\ValidatePostSize::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload: Ukuran file terlalu besar! Maksimal 4MB.'
                ], 413);
            }
            return back()->with('error', 'Ukuran file terlalu besar! Maksimal 4MB.');
        });
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // Temporary debug: Print error directly to screen to avoid Vercel 500 pages hiding the error
            if (env('APP_DEBUG') == true || env('VERCEL') == '1' || str_contains(url()->current(), 'vercel')) {
                die("<h1 style='color:red'>FATAL ERROR DETECTED:</h1><h3>" . $e->getMessage() . "</h3><p>File: " . $e->getFile() . " on line " . $e->getLine() . "</p><textarea style='width:100%; height:300px'>" . $e->getTraceAsString() . "</textarea>");
            }
        });
    })->create();