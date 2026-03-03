<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactMessages = ContactMessage::all();
        return response()->json($contactMessages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $message = ContactMessage::create($validated);

        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        return response()->json($contactMessage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        $contactMessage->update($validated);
        return response()->json($contactMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        $contactMessage->delete();
        return response()->json(['message' => 'Contact message deleted successfully']);
    }
}
