<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Display the Luxury Restaurant Landing & Reservation Page
     */
    public function index(): View
    {
        $branch = Branch::first() ?? (object)[
            'restaurant_name' => "Lazzat Luxury Dine",
            'branch_name' => 'Main Outlet',
            'phone' => '+880 1700-000000',
            'email' => 'info@lazzatdine.com',
            'address' => 'Gulshan Avenue, Dhaka, Bangladesh',
            'currency_symbol' => '৳',
            'opening_hours' => '11:00 AM - 11:30 PM (Daily)'
        ];

        $categories = Category::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_available', true)->with(['variants', 'modifiers']);
            }])
            ->orderBy('sort_order')
            ->get();

        $featuredItems = Item::where('is_available', true)
            ->with(['category', 'variants', 'modifiers'])
            ->take(8)
            ->get();

        $tables = RestaurantTable::where('is_active', true)
            ->orderBy('floor_name')
            ->orderBy('sort_order')
            ->get();

        $stats = [
            'restaurants' => 12,
            'experience_years' => 8,
            'awards_won' => '50+',
            'food_menus' => '200+'
        ];

        $specialistCuisines = [
            [
                'icon' => 'utensils',
                'title' => 'Middle East Food',
                'description' => 'Authentic Arabic mandi, kebabs & fragrant biryanis infused with saffron & spices.'
            ],
            [
                'icon' => 'soup',
                'title' => 'Gourmet Food',
                'description' => 'Masterfully prepared gourmet recipes crafted by award-winning international chefs.'
            ],
            [
                'icon' => 'chef-hat',
                'title' => 'Delicious Food',
                'description' => 'Sizzling grills, slow-cooked royal delicacies & hand-crafted artisan desserts.'
            ],
            [
                'icon' => 'sparkles',
                'title' => 'Fresh Natural',
                'description' => '100% farm-fresh, organic ingredients and pure herbs sourced daily.'
            ],
        ];

        $testimonials = [
            [
                'quote' => '“The royal taste of their Mutton Kacchi and aromatic platters is unmatched. The luxury ambience, gold dining aesthetic, and swift table service make every visit unforgettable!”',
                'name' => 'Dr. Farhana Ahmed',
                'role' => 'Food Connoisseur & Guest',
                'rating' => 5
            ],
            [
                'quote' => '“Remarkable experience! From the instant table reservation to the warm hospitality and gourmet delights, Lazzat sets the gold standard for luxury dining in the city.”',
                'name' => 'Ashfaqul Karim',
                'role' => 'Executive Director',
                'rating' => 5
            ],
            [
                'quote' => '“The presentation of each signature dish is pure art. Perfectly balanced spices, premium cuts of meat, and an intoxicating royal aroma. Truly 5-star experience!”',
                'name' => 'Tahsin Chowdhury',
                'role' => 'Lifestyle Critic',
                'rating' => 5
            ],
        ];

        $blogs = [
            [
                'title' => 'Salad Fresh with Master Dressing Secrets',
                'excerpt' => 'Discover the secret olive glaze and aromatic herb blend used by our master chefs.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef Tanveer',
                'date' => '24 Aug 2026'
            ],
            [
                'title' => 'Secret Behind Perfectly Aged Prime Steak',
                'excerpt' => 'The art of dry-aging and flame grilling to lock in maximum tenderness and rich juiciness.',
                'image' => 'https://images.unsplash.com/photo-1558030006-450675393462?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef Al-Mansoor',
                'date' => '22 Aug 2026'
            ],
            [
                'title' => 'Spaghetti with Seafood & Saffron Infusion',
                'excerpt' => 'How Mediterranean seafood pairs with royal saffron for a rich culinary symphony.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef Antonio',
                'date' => '19 Aug 2026'
            ],
        ];

        return view('landing.index', compact(
            'branch',
            'categories',
            'featuredItems',
            'tables',
            'stats',
            'specialistCuisines',
            'testimonials',
            'blogs'
        ));
    }

    /**
     * Handle Customer Table Reservation Booking
     */
    public function storeReservation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'guest_count' => 'required|integer|min:1|max:50',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|string',
            'table_id' => 'nullable|exists:tables,id',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $branch = Branch::first();

        $reservation = Reservation::create([
            'branch_id' => $branch->id ?? null,
            'table_id' => $validated['table_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'guest_count' => $validated['guest_count'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'confirmed',
        ]);

        $table = $reservation->table;

        return response()->json([
            'success' => true,
            'message' => 'আপনার টেবিল রিজার্ভেশন সফলভাবে কনফার্ম করা হয়েছে!',
            'reservation' => [
                'id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'guest_count' => $reservation->guest_count,
                'date' => $reservation->reservation_date->format('d M, Y'),
                'time' => $reservation->reservation_time,
                'table_name' => $table ? $table->name : 'Royal Dining Area',
            ]
        ]);
    }
}
