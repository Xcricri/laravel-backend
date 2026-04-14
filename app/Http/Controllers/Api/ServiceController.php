<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return response()->json(['data' => $services]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('main_image')) {
            $validate['main_image'] = $this->uploadImage($request->file('main_image'));
        }

        $validate['slug'] = Str::slug($validate['title']);

        $service = Service::create($validate);
        return response()->json([
            'message' => 'Halaman service berhasil dibuat',
            'data' => $service
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return response()->json([
            'data' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validate = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (isset($validate['title'])) {
            $validate['slug'] = Str::slug($validate['title']);
        }

        if ($request->hasFile('main_image')) {
            if ($service->main_image) {
                $this->deleteFile($service->main_image);
            }
            $validate['main_image'] = $this->uploadImage($request->file('main_image'));
        }

        $service->update($validate);

        return response()->json([
            'message' => 'Halaman service berhasil diperbarui',
            'data' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        if ($service->main_image) {
            $this->deleteFile($service->main_image);
        }

        $service->delete();

        return response()->json([
            'message' => 'Halaman service berhasil dihapus',
        ]);
    }


    // Custom function //

    // Admin: get id untuk edit
    public function indexId($id)
    {
        $service = Service::findOrFail($id);
        return response()->json([
            'data' => $service
        ]);
    }

    // Upload gambar
    private function uploadImage($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('service', $filename, 'public');
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
