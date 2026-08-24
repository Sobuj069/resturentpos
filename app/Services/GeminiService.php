<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected array $apiKeys = [];
    protected string $model = 'gemini-1.5-flash';

    public function __construct()
    {
        $keysEnv = env('GEMINI_API_KEYS', '');
        if (!empty($keysEnv)) {
            $this->apiKeys = array_filter(array_map('trim', explode(',', $keysEnv)));
        }

        // Fallback default keys provided by user
        if (empty($this->apiKeys)) {
            $this->apiKeys = [
                'AQ.Ab8RN6LrjB1W4kakFqBNYh35E0rT-oQad4LG6-JSqUcseXHKpg',
                'AQ.Ab8RN6KQEKYy5_qCta9STumtJ5Xti7nj5dHBpLKOviu1radQ9Q',
                'AQ.Ab8RN6IYwjef05Hj5VNevchfmon5W2JqUGDssSUec3tWzYGfYA',
            ];
        }
    }

    /**
     * Send prompt to Gemini API with automatic key rotation and fallback
     */
    public function generateContent(string $systemInstruction, string $promptText): ?string
    {
        $keys = $this->apiKeys;
        shuffle($keys); // Randomize to balance load across keys

        foreach ($keys as $apiKey) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$apiKey}";

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(12)->post($endpoint, [
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $promptText]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 1024,
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                }

                Log::warning("Gemini API key returned status {$response->status()}: " . $response->body());
            } catch (\Throwable $e) {
                Log::warning("Gemini API key failure with key " . substr($apiKey, 0, 8) . "... : " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Parse natural Bangla/English spoken or typed order text into structured items
     */
    public function parseVoiceOrder(string $speechText, array $menuItems): array
    {
        $menuCatalog = json_encode(array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'bangla_name' => $item['bangla_name'] ?? '',
                'price' => $item['selling_price'],
                'variants' => $item['variants'] ?? [],
            ];
        }, $menuItems), JSON_UNESCAPED_UNICODE);

        $systemPrompt = <<<PROMPT
You are an expert Restaurant POS Voice & Natural Language Order Parser for a Bangladeshi restaurant.
Your job is to parse the user's spoken or typed order (in Bangla, Banglish, or English) into an exact JSON structure matching the restaurant's menu catalog.

Available Menu Catalog:
{$menuCatalog}

Rules:
1. Return ONLY valid JSON array without markdown backticks or explanations.
2. Match items based on name or bangla_name or phonetic similarity (e.g. "kacchi" -> "Mutton Kacchi Biryani", "borhani" -> "Special Borhani").
3. Extract quantity, variant if mentioned (e.g., "half", "full", "regular", "large"), special notes (e.g., "less spicy", "parcel/takeaway"), and table_number if mentioned (e.g. "Table 4").
4. Response Format:
{
  "table_number": "4",
  "order_type": "dine_in",
  "items": [
     {
       "item_id": 1,
       "name": "Mutton Kacchi Biryani",
       "quantity": 2,
       "variant_name": "Full",
       "notes": "Less spicy"
     }
  ],
  "confidence": "high"
}
PROMPT;

        $result = $this->generateContent($systemPrompt, "Customer Order Request: " . $speechText);

        if ($result) {
            // Strip markdown block if present
            $cleaned = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($result)));
            $parsed = json_decode($cleaned, true);
            if (is_array($parsed)) {
                return $parsed;
            }
        }

        // Fallback rule-based matching if Gemini is offline
        return $this->fallbackParser($speechText, $menuItems);
    }

    /**
     * Generate daily executive analytics summary in Bangla/English
     */
    public function getDailyExecutiveSummary(array $dailyStats): string
    {
        $statsJson = json_encode($dailyStats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $systemPrompt = "You are a senior Restaurant General Manager and Financial Consultant in Bangladesh. Provide a concise, professional 4-bullet executive summary (in conversational Bangla) highlighting today's sales velocity, peak hour, highest margin dish, and inventory waste control recommendation.";

        $result = $this->generateContent($systemPrompt, "Today's Restaurant Metrics:\n" . $statsJson);
        return $result ?: "আজকের মোট বিক্রয় ৳" . number_format($dailyStats['total_sales'] ?? 0) . "। ভ্যাট সংগৃহীত ৳" . number_format($dailyStats['total_vat'] ?? 0) . "। বেস্ট সেলিং আইটেম কাচ্চি বিরিয়ানি।";
    }

    /**
     * Fallback fuzzy parser when AI network is unavailable
     */
    protected function fallbackParser(string $text, array $menuItems): array
    {
        $items = [];
        $lowerText = mb_strtolower($text, 'UTF-8');

        foreach ($menuItems as $item) {
            $nameLower = mb_strtolower($item['name'], 'UTF-8');
            $banglaLower = mb_strtolower($item['bangla_name'] ?? '', 'UTF-8');

            if (str_contains($lowerText, $nameLower) || ($banglaLower && str_contains($lowerText, $banglaLower))) {
                // Determine quantity from text
                preg_match('/(\d+)\s*(?:টা|টি|plate|ta|x)?/u', $lowerText, $qtyMatch);
                $qty = !empty($qtyMatch[1]) ? (int)$qtyMatch[1] : 1;

                $items[] = [
                    'item_id' => $item['id'],
                    'name' => $item['name'],
                    'quantity' => $qty,
                    'variant_name' => null,
                    'notes' => null,
                ];
            }
        }

        return [
            'table_number' => null,
            'order_type' => str_contains($lowerText, 'parcel') || str_contains($lowerText, 'takeaway') || str_contains($lowerText, 'পার্সেল') ? 'takeaway' : 'dine_in',
            'items' => $items,
            'confidence' => 'medium'
        ];
    }
}
