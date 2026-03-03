<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portofolios = Portfolio::all();
        return response()->json($portofolios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:portfolios',
            'short_description' => 'required',
            'full_content' => 'required',
            'main_image_url' => 'required'
        ]);

        $portfolio = Portfolio::create($validated);

        return response()->json($portfolio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return response()->json($portfolio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:portfolios,slug,' . $portfolio->id,
            'short_description' => 'required',
            'full_content' => 'required',
            'main_image_url' => 'required'
        ]);

        $portfolio->update($validated);
        return response()->json($portfolio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();
        return response()->json(['message' => 'Portfolio deleted successfully']);
    }
}
