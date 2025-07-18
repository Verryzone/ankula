<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MonitorPaymentHealth extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payments:monitor
                           {--alert : Send alerts for issues found}
                           {--summary : Show summary only}';

    /**
     * The console command description.
     */
    protected $description = 'Monitor payment system health and detect issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Payment System Health Monitor');
        $this->line('=================================');

        $issues = [];
        $stats = $this->gatherStats();

        // Display summary
        if ($this->option('summary')) {
            $this->showSummary($stats);
            return;
        }

        // Check for stuck payments
        $stuckPayments = $this->checkStuckPayments();
        if ($stuckPayments > 0) {
            $issues[] = "Found {$stuckPayments} stuck payments";
            $this->warn("⚠️  Found {$stuckPayments} payments stuck in pending status");
        }

        // Check webhook health
        $webhookHealth = $this->checkWebhookHealth();
        if (!$webhookHealth['healthy']) {
            $issues[] = $webhookHealth['message'];
            $this->warn("⚠️  " . $webhookHealth['message']);
        }

        // Check scheduler health
        $schedulerHealth = $this->checkSchedulerHealth();
        if (!$schedulerHealth['healthy']) {
            $issues[] = $schedulerHealth['message'];
            $this->warn("⚠️  " . $schedulerHealth['message']);
        }

        // Show detailed stats
        $this->showDetailedStats($stats);

        // Show recent activity
        $this->showRecentActivity();

        // Summary
        if (empty($issues)) {
            $this->info("\n✅ Payment system is healthy!");
        } else {
            $this->error("\n❌ Issues detected:");
            foreach ($issues as $issue) {
                $this->line("  • {$issue}");
            }

            if ($this->option('alert')) {
                $this->sendAlert($issues);
            }
        }

        // Recommendations
        $this->showRecommendations($stats);
    }

    private function gatherStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'week_orders' => Order::where('created_at', '>=', $thisWeek)->count(),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'success_payments' => Payment::where('status', 'success')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            
            'today_success' => Payment::where('status', 'success')
                                    ->whereDate('updated_at', $today)->count(),
            'today_failed' => Payment::where('status', 'failed')
                                   ->whereDate('updated_at', $today)->count(),
        ];
    }

    private function checkStuckPayments()
    {
        // Payments pending for more than 1 hour
        $stuckThreshold = Carbon::now()->subHour();
        
        return Payment::where('status', 'pending')
                     ->where('created_at', '<', $stuckThreshold)
                     ->count();
    }

    private function checkWebhookHealth()
    {
        // Check recent webhook activity in logs
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return ['healthy' => false, 'message' => 'Log file not found'];
        }

        // Check for recent webhook activity (last 24 hours)
        $recentWebhooks = 0;
        $handle = fopen($logFile, 'r');
        if ($handle) {
            $yesterday = Carbon::yesterday()->format('Y-m-d');
            $today = Carbon::today()->format('Y-m-d');
            
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'MIDTRANS WEBHOOK') !== false && 
                    (strpos($line, $yesterday) !== false || strpos($line, $today) !== false)) {
                    $recentWebhooks++;
                }
            }
            fclose($handle);
        }

        return [
            'healthy' => true, // For now, just return healthy
            'message' => "Recent webhooks: {$recentWebhooks}"
        ];
    }

    private function checkSchedulerHealth()
    {
        $heartbeatFile = storage_path('logs/scheduler-heartbeat.txt');
        
        if (!file_exists($heartbeatFile)) {
            return [
                'healthy' => false, 
                'message' => 'Scheduler heartbeat file not found - scheduler might not be running'
            ];
        }

        $lastHeartbeat = Carbon::createFromTimestamp(filemtime($heartbeatFile));
        $minutesAgo = $lastHeartbeat->diffInMinutes(Carbon::now());

        if ($minutesAgo > 5) {
            return [
                'healthy' => false,
                'message' => "Scheduler last heartbeat was {$minutesAgo} minutes ago"
            ];
        }

        return [
            'healthy' => true,
            'message' => "Scheduler healthy (last heartbeat: {$minutesAgo} minutes ago)"
        ];
    }

    private function showSummary($stats)
    {
        $this->table(['Metric', 'Count'], [
            ['Total Orders', number_format($stats['total_orders'])],
            ['Today Orders', number_format($stats['today_orders'])],
            ['Pending Payments', $stats['pending_payments']],
            ['Success Payments', number_format($stats['success_payments'])],
            ['Failed Payments', number_format($stats['failed_payments'])],
        ]);
    }

    private function showDetailedStats($stats)
    {
        $this->line("\n📊 Payment Statistics:");
        $this->table(['Period', 'Orders', 'Success Rate'], [
            ['Today', $stats['today_orders'], $this->calculateSuccessRate($stats['today_success'], $stats['today_orders'])],
            ['This Week', $stats['week_orders'], 'N/A'],
            ['This Month', $stats['month_orders'], 'N/A'],
            ['Total', number_format($stats['total_orders']), $this->calculateSuccessRate($stats['success_payments'], $stats['total_orders'])],
        ]);

        $this->line("\n💳 Payment Status Distribution:");
        $total = $stats['pending_payments'] + $stats['success_payments'] + $stats['failed_payments'];
        if ($total > 0) {
            $this->table(['Status', 'Count', 'Percentage'], [
                ['Pending', $stats['pending_payments'], round(($stats['pending_payments']/$total)*100, 1) . '%'],
                ['Success', $stats['success_payments'], round(($stats['success_payments']/$total)*100, 1) . '%'],
                ['Failed', $stats['failed_payments'], round(($stats['failed_payments']/$total)*100, 1) . '%'],
            ]);
        }
    }

    private function showRecentActivity()
    {
        $this->line("\n🕐 Recent Activity (Last 24 Hours):");
        
        $recentOrders = Order::with('payment')
                           ->where('created_at', '>=', Carbon::yesterday())
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();

        if ($recentOrders->isEmpty()) {
            $this->line("No recent orders found.");
            return;
        }

        $tableData = [];
        foreach ($recentOrders as $order) {
            $tableData[] = [
                $order->order_number,
                $order->status,
                $order->payment ? $order->payment->status : 'No Payment',
                $order->created_at->format('M j, H:i'),
            ];
        }

        $this->table(['Order', 'Order Status', 'Payment Status', 'Created'], $tableData);
    }

    private function showRecommendations($stats)
    {
        $this->line("\n💡 Recommendations:");
        
        if ($stats['pending_payments'] > 10) {
            $this->line("  • Consider running: php artisan payments:check-all --fix");
        }
        
        if ($stats['failed_payments'] > $stats['success_payments'] * 0.1) {
            $this->line("  • High failure rate detected. Check Midtrans configuration.");
        }
        
        $this->line("  • Monitor logs: tail -f storage/logs/payment-auto-check.log");
        $this->line("  • Check scheduler: php artisan schedule:list");
    }

    private function calculateSuccessRate($success, $total)
    {
        if ($total == 0) return 'N/A';
        return round(($success / $total) * 100, 1) . '%';
    }

    private function sendAlert($issues)
    {
        // Log alert
        Log::warning('Payment system issues detected', [
            'issues' => $issues,
            'timestamp' => now()
        ]);

        $this->info("🚨 Alert logged to system logs");
        
        // Here you can add email/Slack notifications
        // Mail::to('admin@example.com')->send(new PaymentAlertMail($issues));
    }
}
