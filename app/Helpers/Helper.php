<?php

use App\Models\Category;

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


?>