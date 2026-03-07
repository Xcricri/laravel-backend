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
        $pages = Page::all();
        return response()->json(['data' => $pages]);
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

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'main_image_url' => $request->hasFile('main_image')
                ? $this->uploadImage($request->file('main_image'))
                : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Halaman berhasil dibuat',
            'data' => $page
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ];

        // Upload image jika ada yang baru
        if ($request->hasFile('main_image')) {
            $updateData['main_image_url'] = $this->uploadImage($request->file('main_image'));
        }

        $page->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Halaman berhasil diupdate',
            'data' => $page
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Halaman berhasil dihapus'
        ]);
    }

    // Custom function //

    // Admin: List semua pages
    public function adminIndex()
    {
        return Page::latest()->paginate(20);
    }

    // Admin: Get page untuk edit
    public function edit(Page $page)
    {
        return response()->json(['data' => $page]);
    }

    // Public: List semua pages untuk navigation
    public function publicIndex()
    {
        return Page::select('id', 'title', 'slug')->get();
    }

    // Helper
    private function uploadImage($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pages', $filename, 'public'); // disk public
        return Storage::url($path); // otomatis menghasilkan /storage/...
    }
}
