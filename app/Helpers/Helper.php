<?php
use App\Models\Category;
use Illuminate\Support\Facades\Route;

function categoryName($id)
{
      $nama = Category::find($id);
      if ($nama) {
            return $nama->name;
      } else {
            return '-';
      }
}

function formatCurrency($amount)
{
      return 'Rp ' . number_format($amount, 0, ',', '.');
}

function limitText($text, $limit = 50, $end = '...')
{
      if (strlen($text) <= $limit) {
          return $text;
      }
      return substr($text, 0, $limit) . $end;
}

function activeMenu($routeName)
{
      return Route::is($routeName);
}

function selectedDropdown($submenu = [])
{
      foreach ($submenu as $item) {
            if (Route::is($item)) {
                  return true;
            }
      }
      return false;
}

function getOrderStatusBadgeClass($status)
{
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'processing':
            return 'bg-blue-100 text-blue-800 border-blue-200';
        case 'completed':
            return 'bg-green-100 text-green-800 border-green-200';
        case 'cancelled':
            return 'bg-red-100 text-red-800 border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200';
    }
}

function getPaymentStatusBadgeClass($status)
{
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'success':
            return 'bg-green-100 text-green-800 border-green-200';
        case 'failed':
            return 'bg-red-100 text-red-800 border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200';
    }
}

function formatOrderStatus($status)
{
    return ucfirst(str_replace('_', ' ', $status));
}

function cleanupExpiredPayments()
{
    $expiredPayments = \App\Models\Payment::where('status', 'pending')
        ->where('snap_token_expires_at', '<', now())
        ->get();
    
    foreach ($expiredPayments as $payment) {
        $payment->update(['status' => 'failed']);
        \Illuminate\Support\Facades\Log::info('Expired payment marked as failed', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id
        ]);
    }
    
    return $expiredPayments->count();
}

function generateUniqueTransactionId($orderNumber)
{
    return $orderNumber . '-' . time() . '-' . substr(md5(uniqid()), 0, 6);
}

function findOrderByTransactionId($transactionId)
{
    // First, try to find by payment transaction_id
    $payment = \App\Models\Payment::where('transaction_id', $transactionId)->first();
    
    if ($payment) {
        return $payment->order;
    }
    
    // Fallback: Try to extract order_number from transaction_id
    // Format: INV202507181234-AB1C-1721234567-xyz123
    $parts = explode('-', $transactionId);
    if (count($parts) >= 2 && strpos($parts[0], 'INV') === 0) {
        // Reconstruct original order number (first two parts)
        $possibleOrderNumber = $parts[0] . '-' . $parts[1];
        $order = \App\Models\Order::where('order_number', $possibleOrderNumber)->first();
        
        if ($order) {
            return $order;
        }
    }
    
    // Last resort: try direct match (for backward compatibility)
    return \App\Models\Order::where('order_number', $transactionId)->first();
}

?>