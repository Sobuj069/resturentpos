<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebpageContentController extends Controller
{
    /**
     * Display the Webpage Content CMS Dashboard
     */
    public function index(Request $request): View
    {
        $sections = LandingContent::getAllSections();
        return view('webpage-content.index', compact('sections'));
    }

    /**
     * Update specific section content
     */
    public function updateSection(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_key' => 'required|string',
            'content' => 'required|array',
        ]);

        $sectionKey = $validated['section_key'];
        $content = $validated['content'];

        LandingContent::setSection($sectionKey, $content);

        return response()->json([
            'success' => true,
            'message' => 'Section "' . ucfirst(str_replace('_', ' ', $sectionKey)) . '" updated successfully!',
            'data' => LandingContent::getSection($sectionKey)
        ]);
    }

    /**
     * Upload an image for landing page sections
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadDir = public_path('uploads/landing');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'landing_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $url = asset('uploads/landing/' . $filename);

            return response()->json([
                'success' => true,
                'url' => $url,
                'message' => 'Image uploaded successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file received.'
        ], 400);
    }

    /**
     * Reset a section or all sections to default demo content
     */
    public function resetDefaults(Request $request): JsonResponse
    {
        $sectionKey = $request->input('section_key');
        
        if ($sectionKey) {
            LandingContent::where('section_key', $sectionKey)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Section reset to default successfully!',
                'data' => LandingContent::getSection($sectionKey)
            ]);
        }

        LandingContent::truncate();
        return response()->json([
            'success' => true,
            'message' => 'All webpage sections reset to defaults successfully!'
        ]);
    }
}
