<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\LandingContent;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    private function getCommonData(): array
    {
        $branch = Branch::first() ?? (object)[
            'restaurant_name' => "Lezzatos",
            'branch_name' => 'Main Outlet',
            'phone' => '+62 898245124',
            'email' => 'lezzatos@restaurant.com',
            'address' => 'Braga St 28, Bandung, West Java',
            'currency_symbol' => '$',
            'opening_hours' => '08:00 - 23:00 (Daily)'
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

        // Fetch Dynamic CMS Sections from Database
        $cms = LandingContent::getAllSections();

        $hero = $cms['hero'] ?? [];
        $cuisines = $cms['cuisines'] ?? [];
        $about = $cms['about'] ?? [];
        $stats = $cms['stats'] ?? [];
        $sundayOffers = $cms['sunday_offers'] ?? [];
        $recommendedDishes = $cms['recommended_dishes'] ?? [];
        $dottedMenus = $cms['dotted_menus'] ?? [];
        $chefs = $cms['chefs'] ?? [];
        $packages = $cms['packages'] ?? [];
        $servicesList = $cms['services'] ?? [];
        $faqs = $cms['faqs'] ?? [];
        $newsData = $cms['news'] ?? [];
        $contactData = $cms['contact'] ?? [];
        $testimonials = $cms['testimonials'] ?? [];
        $partners = $cms['partners'] ?? [];

        $blogs = [
            [
                'title' => 'Kebab Rice with tomatoes & egg',
                'excerpt' => 'Discover the secret olive glaze and aromatic herb blend used by our master chefs.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef Dany',
                'date' => '24 Aug 2026'
            ],
            [
                'title' => 'How to keep meat always fresh & juicy',
                'excerpt' => 'The art of dry-aging and flame grilling to lock in maximum tenderness and rich juiciness.',
                'image' => 'https://images.unsplash.com/photo-1558030006-450675393462?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef William',
                'date' => '22 Aug 2026'
            ],
            [
                'title' => 'Spaghetti with seafood and saffron infusion',
                'excerpt' => 'How Mediterranean seafood pairs with royal saffron for a rich culinary symphony.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80',
                'author' => 'Chef Antonio',
                'date' => '19 Aug 2026'
            ],
        ];

        return compact(
            'branch', 'categories', 'featuredItems', 'tables', 'cms',
            'hero', 'cuisines', 'about', 'stats', 'sundayOffers',
            'recommendedDishes', 'dottedMenus', 'chefs', 'packages',
            'servicesList', 'faqs', 'newsData', 'contactData', 'testimonials', 'partners', 'blogs'
        );
    }

    public function home(): View { return view('landing.home', $this->getCommonData()); }
    public function menu(): View { return view('landing.menu', $this->getCommonData()); }
    public function about(): View { return view('landing.about', $this->getCommonData()); }
    public function chefs(): View { return view('landing.chefs', $this->getCommonData()); }
    public function reservation(): View { return view('landing.reservation', $this->getCommonData()); }
    public function contact(): View { return view('landing.contact', $this->getCommonData()); }
    public function faq(): View { return view('landing.faq', $this->getCommonData()); }
    public function news(): View { return view('landing.news', $this->getCommonData()); }
    public function services(): View { return view('landing.services', $this->getCommonData()); }
    public function shop(): View { return view('landing.shop', $this->getCommonData()); }

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

        return response()->json([
            'success' => true,
            'message' => 'Your reservation has been booked successfully!',
            'reservation' => [
                'id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'guest_count' => $reservation->guest_count,
                'date' => $reservation->reservation_date->format('d M, Y'),
                'time' => $reservation->reservation_time,
                'table_name' => $reservation->table?->name ?? 'Standard Reserved Area',
                'floor' => $reservation->table?->floor_name ?? 'Main Dining Hall'
            ]
        ]);
    }

    public function storeContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you ' . $validated['name'] . '! Your message has been sent to our management team.'
        ]);
    }
}
