<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired pending payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment cleanup...');
        
        $expiredPayments = Payment::where('status', 'pending')
            ->where('snap_token_expires_at', '<', now())
            ->get();
        
        $count = 0;
        foreach ($expiredPayments as $payment) {
            $payment->update(['status' => 'failed']);
            $count++;
            
            Log::info('Expired payment marked as failed', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'expired_at' => $payment->snap_token_expires_at
            ]);
        }
        
        $this->info("Cleaned up {$count} expired payments.");
        
        return 0;
    }
}
