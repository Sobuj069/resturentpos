<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingContent extends Model
{
    protected $table = 'landing_page_contents';

    protected $fillable = [
        'branch_id',
        'section_key',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Default content templates for all sections
     */
    public static function getDefaultData(): array
    {
        return [
            'hero' => [
                'tagline' => 'Welcome to Lezzatos',
                'title_line1' => 'The Authentic',
                'title_line2' => 'Restaurant & Cafe',
                'description' => 'Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.',
                'btn_text' => 'EXPLORE MENU',
                'btn_url' => '/our-menu',
                'image1' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=600&q=80',
                'image2' => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80',
                'image3' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80',
                'image4' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80',
                'bg_image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1920&q=80',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
            'cuisines' => [
                [
                    'icon' => 'utensils',
                    'title' => 'Middle East Food',
                    'description' => 'Authentic arabic mandi, tender kebabs & fragrant biryanis infused with saffron & spices.'
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
                    'description' => '100% farm-fresh, organic ingredients and pure herbs sourced daily from local farmers.'
                ]
            ],
            'about' => [
                'tagline' => 'About Us',
                'title' => 'Our Story Make History',
                'story_p1' => 'Founded with a passion for preserving imperial gastronomy, Lezzatos combines time-honored royal cooking methods with contemporary culinary finesse.',
                'story_p2' => 'Every marinade is aged to perfection, every biryani pot is slow-cooked over low embers, and every guest is treated like royalty with our warm hospitality and bespoke dining reservations.',
                'image1' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80',
                'image2' => 'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80',
                'founder_quote' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'founder_name' => 'Antonio Lezzato',
                'founder_image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=800&q=80',
            ],
            'stats' => [
                'restaurants' => '12',
                'restaurants_label' => 'Restaurants',
                'experience_years' => '8',
                'experience_label' => 'Years Experience',
                'awards_won' => '50+',
                'awards_label' => 'Award Winner',
                'food_menus' => '200+',
                'menus_label' => 'Food Menus',
                'customers' => '200+',
                'customers_label' => 'Customers',
            ],
            'sunday_offers' => [
                [
                    'title' => 'Sauce Spicy Soup',
                    'price' => '$18',
                    'discount' => '20% OFF',
                    'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=500&q=80'
                ],
                [
                    'title' => 'Vegetables Soup',
                    'price' => '$20',
                    'discount' => '20% OFF',
                    'image' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=500&q=80'
                ],
                [
                    'title' => 'Salmon Pasta',
                    'price' => '$22',
                    'discount' => '20% OFF',
                    'image' => 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=500&q=80'
                ],
                [
                    'title' => 'Salad Box',
                    'price' => '$15',
                    'discount' => '20% OFF',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=500&q=80'
                ]
            ],
            'recommended_dishes' => [
                [
                    'name' => 'Royal Greek Salad',
                    'price' => '$12.00',
                    'description' => 'Fresh feta cheese, Kalamata olives & crisp romaine',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80'
                ],
                [
                    'name' => 'Fettuccine Alfredo',
                    'price' => '$18.00',
                    'description' => 'Rich parmesan cream sauce, truffle oil & herbs',
                    'image' => 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80'
                ],
                [
                    'name' => 'Berry Pancakes',
                    'price' => '$14.00',
                    'description' => 'Fluffy buttermilk stack, raspberry glaze & walnuts',
                    'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80'
                ]
            ],
            'dotted_menus' => [
                'appetizers' => [
                    ['name' => 'Pastel', 'price' => '$20', 'desc' => 'Crispy pastry pockets filled with spiced minced chicken'],
                    ['name' => 'Croquette', 'price' => '$22', 'desc' => 'Golden potato & cheese croquettes with garlic dip'],
                    ['name' => 'Ravioles', 'price' => '$18', 'desc' => 'Handmade ricotta stuffed pasta in sage butter'],
                    ['name' => 'Canapes', 'price' => '$15', 'desc' => 'Bite-sized toasted baguettes with gourmet toppings'],
                    ['name' => 'Agro Dolce', 'price' => '$18', 'desc' => 'Sweet and sour glazed appetizer meatballs'],
                ],
                'main_course' => [
                    ['name' => 'Sirloin Steak', 'price' => '$32', 'desc' => 'Flame-grilled prime sirloin with truffle potato mash'],
                    ['name' => 'Parmesan Spicy Soup', 'price' => '$28', 'desc' => 'Rich parmesan broth with tender meat slices & chili oil'],
                    ['name' => 'Salmon Pasta', 'price' => '$35', 'desc' => 'Pan-seared salmon fillet over creamy fettuccine'],
                    ['name' => 'Chicken Curry Special', 'price' => '$24', 'desc' => 'Slow-cooked chicken in fragrant royal Mughlai gravy'],
                    ['name' => 'Dimsum', 'price' => '$18', 'desc' => 'Steamed artisan dumplings with spicy sesame soy dip'],
                ],
                'desserts' => [
                    ['name' => 'Pancakes Fresche', 'price' => '$16', 'desc' => 'Fluffy stack with wild berries & organic maple drizzle'],
                    ['name' => 'Ice Cream', 'price' => '$12', 'desc' => 'Artisanal Madagascar vanilla & pistachio gelato'],
                    ['name' => 'Cantucci', 'price' => '$15', 'desc' => 'Crunchy almond biscotti served with espresso cream'],
                    ['name' => 'Arricciate Spian', 'price' => '$14', 'desc' => 'Crisp puff pastry filled with sweetened mascarpone'],
                    ['name' => 'Cornetto', 'price' => '$12', 'desc' => 'Warm Italian butter croissant with hazelnut chocolate'],
                ],
                'specials' => [
                    ['name' => 'Greek Salad', 'price' => '$12', 'desc' => 'Fresh lettuce, cucumber, kalamata olives & feta'],
                    ['name' => 'Chicken Spring Soup', 'price' => '$15', 'desc' => 'Slow-simmered chicken broth with fragrant herbs'],
                    ['name' => 'Salmon Salad', 'price' => '$18', 'desc' => 'Smoked Norwegian salmon slices over tossed greens'],
                    ['name' => 'Classic Roast Chicken', 'price' => '$22', 'desc' => 'Oven roasted quarter chicken with herb butter glaze'],
                    ['name' => 'Bitter Ball', 'price' => '$09', 'desc' => 'Crispy dutch-style savoury croquettes with mustard dip'],
                ]
            ],
            'chefs' => [
                [
                    'name' => 'Dany William',
                    'designation' => 'Executive Head Chef',
                    'image' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ],
                [
                    'name' => 'Marco Rossi',
                    'designation' => 'Italian Master Chef',
                    'image' => 'https://images.unsplash.com/photo-1583394293214-28ded15ee548?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ],
                [
                    'name' => 'Sophie Laurent',
                    'designation' => 'Pastry & Dessert Artist',
                    'image' => 'https://images.unsplash.com/photo-1581299894007-aaa50297cf16?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ],
                [
                    'name' => 'Ahmed Al-Mansoor',
                    'designation' => 'Mughlai & Grill Specialist',
                    'image' => 'https://images.unsplash.com/photo-1566554273541-37a9ca77b91f?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ],
                [
                    'name' => 'Chen Wei',
                    'designation' => 'Asian Fusion Master',
                    'image' => 'https://images.unsplash.com/photo-1607631568010-a87245c0daf8?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ],
                [
                    'name' => 'Elena Vasquez',
                    'designation' => 'Sommelier & Host',
                    'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=600&q=80',
                    'facebook' => '#',
                    'instagram' => '#',
                    'twitter' => '#'
                ]
            ],
            'packages' => [
                [
                    'id' => 'single',
                    'name' => 'Single',
                    'price' => '$29.99',
                    'billing' => '/ day',
                    'is_featured' => false,
                    'features' => [
                        '1 Signature Main Course',
                        '1 Gourmet Appetizer / Soup',
                        '1 Choice of Beverage',
                        'Complimentary Dessert',
                        'Reserved Priority Seating'
                    ]
                ],
                [
                    'id' => 'couple',
                    'name' => 'Couple',
                    'price' => '$59.99',
                    'billing' => '/ day',
                    'is_featured' => true,
                    'features' => [
                        '2 Signature Main Courses',
                        '2 Gourmet Appetizers',
                        '2 Special Mocktails',
                        'Deluxe Dessert Platter',
                        'Candle Light Table Decor'
                    ]
                ],
                [
                    'id' => 'family',
                    'name' => 'Family',
                    'price' => '$99.99',
                    'billing' => '/ day',
                    'is_featured' => false,
                    'features' => [
                        '4 Signature Main Courses',
                        'Family Sized Appetizer Basket',
                        '4 Mocktails / Juices',
                        'Chef Special Family Cake',
                        'Private Family Booth Reserved'
                    ]
                ]
            ],
            'services' => [
                [
                    'icon' => 'soup',
                    'title' => 'New Weekly Menu',
                    'desc' => 'Exciting seasonal chef creations introduced every single week.'
                ],
                [
                    'icon' => 'chef-hat',
                    'title' => 'Professional Chef',
                    'desc' => 'Culinary masters with Michelin-level kitchen precision and hygiene.'
                ],
                [
                    'icon' => 'truck',
                    'title' => 'Free Shipping Delivery',
                    'desc' => 'Piping hot packaging delivered swiftly across the metropolitan area.'
                ],
                [
                    'icon' => 'armchair',
                    'title' => 'Comfortable Dining Room',
                    'desc' => 'Ambient lighting, private acoustic alcoves, and luxurious comfort.'
                ]
            ],
            'faqs' => [
                [
                    'question' => 'What is Lezzatos ?',
                    'answer' => 'Lezzatos is an authentic luxury restaurant and cafe offering imperial gourmet culinary masterpieces, royal biryanis, flame grilled steaks, artisan desserts, and bespoke dining reservations.'
                ],
                [
                    'question' => 'How to make a food reservation?',
                    'answer' => 'You can easily reserve your table through our online reservation system. Simply select your preferred date, time slot, guest count, and table area. You will receive an instant confirmation on screen.'
                ],
                [
                    'question' => 'Where is the restaurant address located?',
                    'answer' => 'We are located in the heart of the city at Braga Street 28, Bandung, West Java. We offer complimentary valet parking for all our dining guests.'
                ],
                [
                    'question' => 'How to cancel an order?',
                    'answer' => 'To cancel or reschedule a table reservation, please call our direct hotline at +62 898245124 at least 2 hours before your scheduled dining time.'
                ],
                [
                    'question' => 'Where to contact if having problems?',
                    'answer' => 'Our customer support team is available daily from 08:00 to 23:00 via email at lezzatos@restaurant.com or by submitting the contact form on our website.'
                ]
            ],
            'news' => [
                'featured' => [
                    'title' => 'Our CFO, Andrew Jonshan Announces Expansion Plans',
                    'badge' => 'Featured',
                    'excerpt' => 'Lezzatos is proud to announce the launch of three new luxury flagship dining locations with state of the art private dining suites.',
                    'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1000&q=80',
                    'time_ago' => 'TODAY'
                ],
                'mini' => [
                    [
                        'title' => 'Our Chef Win National Cooking Award',
                        'time_ago' => '7 MINS AGO',
                        'desc' => 'Celebrating our culinary triumphs in culinary artistry.',
                        'image' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=300&q=80'
                    ],
                    [
                        'title' => 'New Menu: Kebab Rice in the House',
                        'time_ago' => '2 HOURS AGO',
                        'desc' => 'Try our royal saffron-infused spiced basmati bowl.',
                        'image' => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=300&q=80'
                    ],
                    [
                        'title' => 'Comfortable Place for Candle Light Dining',
                        'time_ago' => '1 DAY AGO',
                        'desc' => 'Romantic atmosphere for your private moments.',
                        'image' => 'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=300&q=80'
                    ]
                ]
            ],
            'contact' => [
                'phone' => '+62 898245124',
                'email' => 'lezzatos@restaurant.com',
                'address' => 'Braga St 28, Bandung, West Java',
                'opening_hours' => [
                    'mon_fri' => '08:00 - 22:00',
                    'sat' => '08:00 - 23:00',
                    'sun' => 'Closed'
                ],
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'instagram' => 'https://instagram.com',
                    'tiktok' => 'https://tiktok.com',
                    'linkedin' => 'https://linkedin.com',
                    'twitter' => 'https://twitter.com',
                    'youtube' => 'https://youtube.com',
                    'whatsapp' => 'https://wa.me/62898245124'
                ]
            ],
            'testimonials' => [
                [
                    'quote' => 'The royal taste of their delicacies and aromatic platters is unmatched. The luxury ambience, gold dining aesthetic, and swift table service make every visit unforgettable!',
                    'name' => 'Jonathan Xander',
                    'role' => 'Food Connoisseur & Guest',
                    'rating' => 5
                ],
                [
                    'quote' => 'Remarkable experience! From the instant table reservation to the warm hospitality and gourmet delights, Lezzatos sets the gold standard for luxury dining.',
                    'name' => 'Farhana Ahmed',
                    'role' => 'Executive Director',
                    'rating' => 5
                ],
                [
                    'quote' => 'The presentation of each signature dish is pure art. Perfectly balanced spices, premium cuts of meat, and an intoxicating royal aroma. Truly a 5-star experience!',
                    'name' => 'Ashfaqul Karim',
                    'role' => 'Lifestyle Critic',
                    'rating' => 5
                ],
                [
                    'quote' => 'An absolute culinary paradise! The ambience is regal, the servers are attentive and polite, and the signature dum biryani and grilled kebabs are to die for.',
                    'name' => 'Sarah Jenkins',
                    'role' => 'Gourmet Traveler',
                    'rating' => 5
                ],
                [
                    'quote' => 'Exquisite taste and opulent atmosphere! We hosted our company anniversary dinner here, and every single guest was blown away by the service and culinary artistry.',
                    'name' => 'Tariqul Islam',
                    'role' => 'Tech Entrepreneur',
                    'rating' => 5
                ]
            ],
            'partners' => [
                'tagline' => 'Official Collaborations',
                'title' => 'Our Esteemed Partners & Sponsors',
                'items' => [
                    ['name' => 'Coca-Cola', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg', 'url' => 'https://www.coca-cola.com'],
                    ['name' => 'Starbucks', 'logo' => 'https://upload.wikimedia.org/wikipedia/en/d/d3/Starbucks_Corporation_Logo_2011.svg', 'url' => 'https://www.starbucks.com'],
                    ['name' => 'Foodpanda', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/b/b3/Foodpanda_logo.svg', 'url' => 'https://www.foodpanda.com'],
                    ['name' => 'Uber Eats', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/cc/Uber_Eats_2020_logo.svg', 'url' => 'https://www.ubereats.com'],
                    ['name' => 'Pepsi', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/6/68/Pepsi_logo_2014.svg', 'url' => 'https://www.pepsi.com'],
                    ['name' => 'Nestlé', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/4/4d/Nestle_textlogo.svg', 'url' => 'https://www.nestle.com'],
                    ['name' => 'McDonald\'s', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/3/36/McDonald%27s_Golden_Arches.svg', 'url' => 'https://www.mcdonalds.com'],
                    ['name' => 'Heineken', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/23/Heineken_Logo.svg', 'url' => 'https://www.heineken.com'],
                    ['name' => 'Red Bull', 'logo' => 'https://upload.wikimedia.org/wikipedia/en/f/f5/RedBullEnergyDrink.svg', 'url' => 'https://www.redbull.com'],
                    ['name' => 'Lavazza', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/0/07/Lavazza_logo.svg', 'url' => 'https://www.lavazza.com'],
                    ['name' => 'Mastercard', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg', 'url' => 'https://www.mastercard.com'],
                    ['name' => 'Michelin Guide', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/1/10/Michelin_Logo.svg', 'url' => 'https://guide.michelin.com'],
                    ['name' => 'S.Pellegrino', 'logo' => 'https://upload.wikimedia.org/wikipedia/en/9/91/San_Pellegrino_logo.svg', 'url' => 'https://www.sanpellegrino.com']
                ]
            ]
        ];
    }

    /**
     * Get section content with fallback to default
     */
    public static function getSection(string $key, ?int $branchId = null): array
    {
        $defaults = self::getDefaultData();
        $default = $defaults[$key] ?? [];

        $record = self::where('section_key', $key)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->first();

        if ($record && !empty($record->content)) {
            return is_array($record->content) ? array_replace_recursive($default, $record->content) : $default;
        }

        return $default;
    }

    /**
     * Save section content
     */
    public static function setSection(string $key, array $data, ?int $branchId = null): self
    {
        return self::updateOrCreate(
            ['section_key' => $key, 'branch_id' => $branchId],
            ['content' => $data]
        );
    }

    /**
     * Get all sections merged with defaults
     */
    public static function getAllSections(?int $branchId = null): array
    {
        $defaults = self::getDefaultData();
        $records = self::when($branchId, fn($q) => $q->where('branch_id', $branchId))->get();

        foreach ($records as $rec) {
            if (isset($defaults[$rec->section_key]) && !empty($rec->content)) {
                $defaults[$rec->section_key] = array_replace_recursive($defaults[$rec->section_key], $rec->content);
            }
        }

        return $defaults;
    }
}
