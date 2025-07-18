<?php

namespace App\Http\Controllers;

use App\Models\DashboardHighlight;
use App\Models\DashboardContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManagementHighlightController extends Controller
{
    public function index()
    {
        $highlights = DashboardHighlight::ordered()->get();
        $contents = DashboardContent::ordered()->get();
        return view('management.pages.highlight.index', compact('highlights', 'contents'));
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

    // Dashboard Content Management Methods
    public function contentCreate()
    {
        return view('management.pages.highlight.content-create');
    }

    public function contentStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'price' => 'nullable|numeric|min:0',
            'price_display' => 'nullable|string|max:100',
            'link' => 'nullable|url',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'size' => 'required|in:small,medium,large',
            'type' => 'required|in:promo,featured,category',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');
        
        // Set sort order to last
        $lastOrder = DashboardContent::max('sort_order') ?? 0;
        $data['sort_order'] = $lastOrder + 1;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('contents', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        DashboardContent::create($data);

        return redirect()->route('management.highlight.index')
            ->with('success', 'Konten berhasil ditambahkan!');
    }

    public function contentEdit(DashboardContent $content)
    {
        return view('management.pages.highlight.content-edit', compact('content'));
    }

    public function contentUpdate(Request $request, DashboardContent $content)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'price' => 'nullable|numeric|min:0',
            'price_display' => 'nullable|string|max:100',
            'link' => 'nullable|url',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'size' => 'required|in:small,medium,large',
            'type' => 'required|in:promo,featured,category',
            'is_active' => 'boolean',
            'remove_image' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
                Storage::disk('public')->delete($content->image_path);
            }
            $data['image_path'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
                Storage::disk('public')->delete($content->image_path);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('contents', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $content->update($data);

        return redirect()->route('management.highlight.index')
            ->with('success', 'Konten berhasil diupdate!');
    }

    public function contentDestroy(DashboardContent $content)
    {
        // Delete image if exists
        if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
            Storage::disk('public')->delete($content->image_path);
        }

        $content->delete();

        return redirect()->route('management.highlight.index')
            ->with('success', 'Konten berhasil dihapus!');
    }

    public function contentToggleActive(DashboardContent $content)
    {
        $content->update(['is_active' => !$content->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status konten berhasil diubah!',
            'is_active' => $content->is_active
        ]);
    }

    public function contentUpdateOrder(Request $request)
    {
        $contents = $request->input('contents', []);

        foreach ($contents as $index => $id) {
            DashboardContent::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan konten berhasil diupdate!'
        ]);
    }
}
