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


?>