<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages',
            'content' => 'required|string',
            'main_image_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('main_image_url')) {
            $validate['main_image_url'] = $request->file('main_image_url')->store('pages', 'public');
        }

        $page = Page::create($validate);
        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return response()->json(['data' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'main_image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('main_image_url')) {
            // Delete old image if exists
            if ($page->main_image_url) {
                Storage::disk('public')->delete($page->main_image_url);
            }

            $main_image_url_path = $request->file('main_image_url')->store('pages', 'public');
            $validate['main_image_url'] = $main_image_url_path;
        }

        $page->update($validate);
        return response()->json($page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }
}
