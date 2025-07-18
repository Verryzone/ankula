<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DashboardHighlight;
use App\Models\DashboardContent;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function fetch()
    {
        $products = Product::all();
        $highlights = DashboardHighlight::active()->ordered()->get();
        $contents = DashboardContent::active()->ordered()->get();
        
        return view('pages.dashboard.app', compact('products', 'highlights', 'contents'));
    }
}
