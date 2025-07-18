<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

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
        // Payment status auto-check setiap 3 menit (lebih frequent untuk better UX)
        $schedule->command('payments:check-all --fix')
                 ->everyThreeMinutes()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/payment-auto-check.log'))
                 ->onSuccess(function () {
                     Log::info('Payment auto-check completed successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Payment auto-check failed');
                 });

        // Cleanup expired payments setiap 2 jam
        $schedule->command('payments:cleanup-expired')
                 ->everyTwoHours()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/payment-cleanup.log'));

        // Health check untuk memastikan scheduler berjalan
        $schedule->call(function () {
            Log::info('Scheduler health check - ' . now()->toDateTimeString());
            // Update file timestamp untuk monitoring
            touch(storage_path('logs/scheduler-heartbeat.txt'));
        })->everyMinute()
          ->name('scheduler-heartbeat');

        // Weekly maintenance
        $schedule->command('optimize:clear')
                 ->weekly()
                 ->sundays()
                 ->at('02:00')
                 ->appendOutputTo(storage_path('logs/maintenance.log'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
