<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebpageContentController extends Controller
{
    /**
     * Ensure only SuperAdmin can access and modify Webpage Content
     */
    private function authorizeSuperAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'অননুমোদিত অ্যাক্সেস! শুধুমাত্র সুপার-অ্যাডমিন এই পেজটি অ্যাক্সেস ও এডিট করতে পারেন।');
        }
    }

    /**
     * Display the Webpage Content CMS Dashboard
     */
    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();
        $sections = LandingContent::getAllSections();
        return view('webpage-content.index', compact('sections'));
    }

    /**
     * Update specific section content
     */
    public function updateSection(Request $request): JsonResponse
    {
        $this->authorizeSuperAdmin();

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
        $this->authorizeSuperAdmin();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $file = $request->file('image');
        $fileName = 'landing_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/landing');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);
        $url = asset('uploads/landing/' . $fileName);

        return response()->json([
            'success' => true,
            'url' => $url,
            'message' => 'ছবি সফলভাবে আপলোড হয়েছে!'
        ]);
    }

    /**
     * Reset all sections to factory default template
     */
    public function resetToDefault(Request $request): JsonResponse
    {
        $this->authorizeSuperAdmin();

        LandingContent::truncate();

        return response()->json([
            'success' => true,
            'message' => 'সকল ওয়েবপেজ সেকশন সফলভাবে ফ্যাক্টরি ডিফল্ট মানে রিসেট করা হয়েছে!'
        ]);
    }
}
