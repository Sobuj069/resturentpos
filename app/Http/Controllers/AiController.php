<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Parse natural language Bangla/English order via Gemini AI
     */
    public function parseVoiceOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|min:2',
        ]);

        $menuItems = Item::where('is_available', true)
            ->with('variants')
            ->get(['id', 'name', 'bangla_name', 'selling_price'])
            ->toArray();

        $parsed = $this->geminiService->parseVoiceOrder($validated['prompt'], $menuItems);

        return response()->json([
            'success' => true,
            'original_prompt' => $validated['prompt'],
            'parsed' => $parsed,
        ]);
    }
}
