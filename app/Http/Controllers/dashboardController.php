<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function fetch()
    {
        $products = Product::all();
        
        return view('pages.dashboard.app', compact('products'));
    }
}
