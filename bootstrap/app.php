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
        $middleware->validateCsrfTokens(except: [
            'hook/payment/webhook',
            'hook/sepay/ipn',
        ]);
        $middleware->encryptCookies(except: [
            'vietgo_remember_email',
        ]);
        $middleware->alias([
            'kiem-tra-vai-tro' => \App\Http\Middleware\KiemTraVaiTro::class,
            'kiem-tra-quan-tri' => \App\Http\Middleware\KiemTraQuanTri::class,
            'kiem-tra-khach-hang' => \App\Http\Middleware\KiemTraKhachHang::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
