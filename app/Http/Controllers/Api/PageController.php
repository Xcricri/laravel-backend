<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Page::all();
        return response()->json(['data' => $response]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $validated['main_image_url'] = $this->uploadImage($request->file('main_image'));
        }

        $validated['slug'] = Str::slug($validated['title']);

        $page = Page::create($validated);

        return response()->json([
            'message' => 'Halaman berhasil dibuat',
            'data' => $page
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('main_image')) {
            if ($page->main_image_url) {
                $this->deleteFile($page->main_image_url);
            }
            $validated['main_image_url'] = $this->uploadImage($request->file('main_image'));
        }

        $page->update($validated);

        return response()->json([
            'message' => 'Halaman berhasil diupdate',
            'data' => $page
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if ($page->main_image_url) {
            $this->deleteFile($page->main_image_url);
        }

        $page->delete();

        return response()->json([
            'message' => 'Halaman berhasil dihapus'
        ]);
    }

    // Custom Functions //

    // Admin: Get page untuk edit
    public function getId($id)
    {
        $page = Page::findOrFail($id);
        return response()->json([
            'data' => $page
        ]);
    }

    // Public: List semua pages untuk navigation
    public function publicIndex()
    {
        return Page::select('id', 'title', 'slug')->get();
    }

    // Upload gambar
    private function uploadImage($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('pages', $filename, 'public');
    }

    // Hapus gambar
    private function deleteFile($url)
    {
        $path = ltrim(str_replace('/storage/', '', $url), '/');
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
