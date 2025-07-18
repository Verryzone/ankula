<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // mendaftarkan middleware yang akan digunakan
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        
        // Exclude webhook endpoint from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'payment/notification'
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Auto-check payment status every 5 minutes
        $schedule->command('payments:check-all --fix')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground();
                 
        // Clean up expired payments daily
        $schedule->command('payments:cleanup-expired')
                 ->daily()
                 ->at('03:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
