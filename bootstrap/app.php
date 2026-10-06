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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->is('login') || $request->is('/')) {
                return redirect()->route('login')
                    ->with('info', 'Sesi halaman login Anda telah diperbarui. Silakan masukkan email dan password untuk masuk.');
            }
            return redirect()->back()
                ->with('info', 'Sesi halaman telah disegarkan. Silakan coba kembali tindakan Anda.');
        });
    })->create();
