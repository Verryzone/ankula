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

function activeMenu($routeName)
{
      return Route::is($routeName) ? 'bg-gray-200' : 'bg-gray-50';
}

function selectedDropdown($submenu = [])
{
      foreach ($submenu as $item) {
            if (Route::is($item)) {
                  return '';
            }
      }
      return 'hidden';
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

?>