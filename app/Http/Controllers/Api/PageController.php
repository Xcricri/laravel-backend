<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return response()->json($pages);
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
        ]);

        $page = Page::create($validate);
        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return response()->json($page);
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
        ]);

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
