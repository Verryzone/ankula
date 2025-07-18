<?php

namespace App\Http\Controllers;

use App\Models\DashboardHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManagementHighlightController extends Controller
{
    public function index()
    {
        $highlights = DashboardHighlight::ordered()->get();
        return view('management.pages.highlight.index', compact('highlights'));
    }

    public function create()
    {
        return view('management.pages.highlight.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'button_text' => 'required|string|max:50',
            'button_link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('highlights', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        DashboardHighlight::create($data);

        return redirect()->route('management.highlight.index')
            ->with('success', 'Highlight berhasil ditambahkan!');
    }

    public function show(DashboardHighlight $highlight)
    {
        return view('management.pages.highlight.show', compact('highlight'));
    }

    public function edit(DashboardHighlight $highlight)
    {
        return view('management.pages.highlight.edit', compact('highlight'));
    }

    public function update(Request $request, DashboardHighlight $highlight)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'button_text' => 'required|string|max:50',
            'button_link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($highlight->image_path && Storage::disk('public')->exists($highlight->image_path)) {
                Storage::disk('public')->delete($highlight->image_path);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('highlights', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $highlight->update($data);

        return redirect()->route('management.highlight.index')
            ->with('success', 'Highlight berhasil diupdate!');
    }

    public function destroy(DashboardHighlight $highlight)
    {
        // Delete image if exists
        if ($highlight->image_path && Storage::disk('public')->exists($highlight->image_path)) {
            Storage::disk('public')->delete($highlight->image_path);
        }

        $highlight->delete();

        return redirect()->route('management.highlight.index')
            ->with('success', 'Highlight berhasil dihapus!');
    }

    public function toggleActive(DashboardHighlight $highlight)
    {
        $highlight->update(['is_active' => !$highlight->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status highlight berhasil diubah!',
            'is_active' => $highlight->is_active
        ]);
    }

    public function updateOrder(Request $request)
    {
        $highlights = $request->input('highlights', []);

        foreach ($highlights as $index => $id) {
            DashboardHighlight::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan highlight berhasil diupdate!'
        ]);
    }
}
