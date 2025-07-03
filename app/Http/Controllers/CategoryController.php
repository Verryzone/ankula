<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class CategoryController extends Controller
{
    public function list()
    {
        $categories = Category::all();
        return view('management.pages.category.list', compact('categories'));
    }

    public function add(Request $request)
    {
        $data = [
            'name' => $request->name,
            'slug' => str_replace(' ', '-', strtolower($request->name)),
            'description' => $request->description,
        ];

        Category::create($data);
        return redirect()->route('management.category.list')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category = Category::findOrFail($id);

        if($category->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kategori',
            ]);
        }
        
    }

    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->delete();
        return redirect()->route('management.category.list')->with('success', 'Kategori berhasil dihapus');
    }
}
