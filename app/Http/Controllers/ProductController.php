<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        
        return view('management.pages.product.list', compact('products', 'categories'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric' // perbaikan: gunakan 'stock' bukan 'stok'
        ]);

        $storeImage = $request->file('image')->store('products/images', 'public');
        $imageName = basename($storeImage);

        $data = [
            'name' => $request->name,
            'slug' => str_replace(' ', '-', strtolower($request->name)),
            'category_id' => 1,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName
        ];

        $add = Product::create($data);
        if ($add) {
            return redirect()->route('management.product.list')->with('success', 'Produk berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan produk');
        }
    }

    public function edit(Request $request)
    {
        $produk = Product::find($request->id);
        if ($produk) {
            return response()->json($produk);
        } else {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        $produk = Product::find($request->id);
        if ($produk) {
            if ($request->hasFile('image')) {
                $storeImage = $request->file('image')->store('products/images', 'public');
                $imageName = basename($storeImage);
                $produk->image = $imageName;
            }

            $produk->name = $request->name;
            $produk->slug = str_replace(' ', '-', strtolower($request->name));
            $produk->description = $request->description;
            $produk->price = $request->price;
            $produk->stock = $request->stock;

            if ($produk->save()) {
                return response()->json(['success' => 'Produk berhasil diperbarui'], 200);
            } else {
                return response()->json(['error' => 'Gagal memperbarui produk'], 500);
            }
        } else {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }
    }

    public function destroy(Request $request)
    {
        $produk = Product::find($request->id);
        if ($produk) {
            $produk->delete();
        }
        return redirect()->route('management.product.list');
    }

    function loadDashboard()
    {
        $products = Product::all();

        return view('public.pages.dashboard.app', ['products' => $products]);
    }
}
