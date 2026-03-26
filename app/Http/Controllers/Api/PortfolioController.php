<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portofolios = Portfolio::with('images')->latest()->get();
        return response()->json([
            'data' => $portofolios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_content' => 'required|string',
            'project_date' => 'required|date',
            'main_image' => 'required|image|max:2048',
            'images.*' => 'image|max:2048',
            'captions.*' => 'nullable|string|max:255'
        ]);

        $validated['main_image_url'] = $this->uploadImage($request->file('main_image'));

        $validated['slug'] = Str::slug($validated['title']);

        $portfolio = Portfolio::create($validated);

        $this->saveImages($portfolio->id, $request);

        return response()->json([
            'message' => 'Portfolio berhasil ditambahkan!',
            'data' => $portfolio
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->with('images')->firstOrFail();

        return response()->json([
            'data' => $portfolio
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'short_description' => 'sometimes|string|max:500',
            'full_content' => 'sometimes|string',
            'project_date' => 'sometimes|date',
            'main_image' => 'nullable|image|max:2048',
            'images.*' => 'image|max:2048',
            'captions.*' => 'nullable|string|max:255'
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('main_image')) {
            if ($portfolio->main_image_url) {
                $this->deleteFile($portfolio->main_image_url);
            }

            $validated['main_image_url'] = $this->uploadImage($request->file('main_image'));
        }

        $portfolio->update($validated);

        $this->saveImages($portfolio->id, $request);

        return response()->json([
            'message' => 'Portfolio berhasil diupdate!',
            'data' => $portfolio->load('images')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->main_image_url) {
            $this->deleteFile($portfolio->main_image_url);
        }

        $portfolio->delete();

        return response()->json([
            'message' => 'Portfolio deleted successfully'
        ]);
    }

    // Custom functions //

    public function getId($id)
    {
        $response = Portfolio::with('images')->findOrFail($id);
        return response()->json([
            'data' => $response
        ]);
    }

    // Upload Image
    private function uploadImage($file)
    {
        return $file->store('portfolios', 'public');
    }

    // Menyimpan banyak image
    private function saveImages($portfolioId, Request $request)
    {
        if ($request->hasFile('images')) {

            // Hapus gallery images lama
            $oldImages = PortfolioImage::where('portfolio_id', $portfolioId)->get();
            foreach ($oldImages as $oldImage) {
                $this->deleteFile($oldImage->image_url);
                $oldImage->delete();
            }

            // Simpan gambar baru
            foreach ($request->file('images') as $index => $image) {
                PortfolioImage::create([
                    'portfolio_id' => $portfolioId,
                    'image_url' => $this->uploadImage($image),
                    'caption' => $request->captions[$index] ?? null
                ]);
            }
        }
    }

    // Menghapus image
    private function deleteFile($url)
    {
        $path = str_replace('/storage/', '', $url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
