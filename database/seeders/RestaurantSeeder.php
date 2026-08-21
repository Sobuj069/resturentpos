<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\ItemRecipe;
use App\Models\ItemVariant;
use App\Models\Modifier;
use App\Models\RestaurantTable;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Branch
        $branch = Branch::create([
            'name' => "Sultan's Dine & Grill - Dhanmondi",
            'code' => 'DHD01',
            'phone' => '+880 1711-223344',
            'email' => 'dhanmondi@sultanspos.com',
            'address' => 'House #34, Road #10/A, Dhanmondi R/A, Dhaka-1209, Bangladesh',
            'bin_number' => '001928374-0102',
            'mushak_code' => '6.3',
            'default_vat_rate' => 5.00,
            'default_sd_rate' => 0.00,
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'is_active' => true,
        ]);

        // 2. Users
        $admin = User::create([
            'branch_id' => $branch->id,
            'name' => 'MD. Kamrul Hasan (Admin)',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'pin_code' => '9999',
            'phone' => '01711000001',
        ]);

        $cashier = User::create([
            'branch_id' => $branch->id,
            'name' => 'Rafiqul Islam (Cashier)',
            'email' => 'cashier@pos.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'pin_code' => '1234',
            'phone' => '01711000002',
        ]);

        $waiter = User::create([
            'branch_id' => $branch->id,
            'name' => 'Tanvir Ahmed (Waiter)',
            'email' => 'waiter@pos.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'pin_code' => '2222',
            'phone' => '01711000003',
        ]);

        $chef = User::create([
            'branch_id' => $branch->id,
            'name' => 'Ustad Karim (Head Chef)',
            'email' => 'kitchen@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kitchen',
            'pin_code' => '3333',
            'phone' => '01711000004',
        ]);

        // 3. Modifiers
        $modExtraMeat = Modifier::create(['branch_id' => $branch->id, 'name' => 'Extra Meat (অতিরিক্ত মাংস)', 'price' => 120.00]);
        $modExtraBorhani = Modifier::create(['branch_id' => $branch->id, 'name' => 'Extra Borhani (বোরহানি)', 'price' => 80.00]);
        $modExtraCheese = Modifier::create(['branch_id' => $branch->id, 'name' => 'Extra Cheese (চিজ)', 'price' => 50.00]);
        $modLessSpicy = Modifier::create(['branch_id' => $branch->id, 'name' => 'Less Spicy (কম ঝাল)', 'price' => 0.00]);
        $modExtraSpicy = Modifier::create(['branch_id' => $branch->id, 'name' => 'Extra Spicy (বেশি ঝাল)', 'price' => 0.00]);

        // 4. Categories & Items
        $catKacchi = Category::create([
            'branch_id' => $branch->id,
            'name' => 'Kacchi & Biryani',
            'bangla_name' => 'কাচ্চি ও বিরিয়ানি',
            'slug' => 'kacchi-biryani',
            'color' => '#d97706',
            'sort_order' => 1,
        ]);

        $catKebab = Category::create([
            'branch_id' => $branch->id,
            'name' => 'Kebab & Grills',
            'bangla_name' => 'কাবাব ও গ্রিল',
            'slug' => 'kebab-grills',
            'color' => '#dc2626',
            'sort_order' => 2,
        ]);

        $catCurry = Category::create([
            'branch_id' => $branch->id,
            'name' => 'Curries & Naan',
            'bangla_name' => 'কারি ও নান',
            'slug' => 'curries-naan',
            'color' => '#059669',
            'sort_order' => 3,
        ]);

        $catDrinks = Category::create([
            'branch_id' => $branch->id,
            'name' => 'Desserts & Drinks',
            'bangla_name' => 'ডেজার্ট ও ড্রিংকস',
            'slug' => 'desserts-drinks',
            'color' => '#2563eb',
            'sort_order' => 4,
        ]);

        // Items
        $itemKacchi = Item::create([
            'category_id' => $catKacchi->id,
            'branch_id' => $branch->id,
            'name' => 'Mutton Kacchi Biryani',
            'bangla_name' => 'মাটন কাচ্চি বিরিয়ানি',
            'sku' => 'KAC-001',
            'barcode' => '8940001001',
            'image' => '/images/food/kacchi.jpg',
            'description' => 'Aromatic basmati rice cooked with tender mutton chunks and traditional spices.',
            'cost_price' => 280.00,
            'selling_price' => 450.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'main_kitchen',
            'has_variants' => true,
            'is_featured' => true,
        ]);
        $varKacchiHalf = ItemVariant::create(['item_id' => $itemKacchi->id, 'name' => 'Half (১:১)', 'cost_price' => 220.00, 'price' => 380.00]);
        $varKacchiFull = ItemVariant::create(['item_id' => $itemKacchi->id, 'name' => 'Full (১:২)', 'cost_price' => 320.00, 'price' => 540.00]);
        $itemKacchi->modifiers()->attach([$modExtraMeat->id, $modExtraBorhani->id, $modLessSpicy->id, $modExtraSpicy->id]);

        $itemTehari = Item::create([
            'category_id' => $catKacchi->id,
            'branch_id' => $branch->id,
            'name' => 'Old Dhaka Beef Tehari',
            'bangla_name' => 'পুরান ঢাকার বিফ তেহারী',
            'sku' => 'TEH-002',
            'barcode' => '8940001002',
            'image' => '/images/food/tehari.jpg',
            'description' => 'Authentic mustard oil cooked chinigura rice with spicy mustard beef cubes.',
            'cost_price' => 160.00,
            'selling_price' => 290.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'main_kitchen',
            'has_variants' => true,
            'is_featured' => true,
        ]);
        $varTehariHalf = ItemVariant::create(['item_id' => $itemTehari->id, 'name' => 'Half (১:১)', 'cost_price' => 140.00, 'price' => 260.00]);
        $varTehariFull = ItemVariant::create(['item_id' => $itemTehari->id, 'name' => 'Full (১:২)', 'cost_price' => 220.00, 'price' => 390.00]);
        $itemTehari->modifiers()->attach([$modExtraMeat->id, $modLessSpicy->id, $modExtraSpicy->id]);

        $itemRoast = Item::create([
            'category_id' => $catKacchi->id,
            'branch_id' => $branch->id,
            'name' => 'Morog Polao with Biye Bari Roast',
            'bangla_name' => 'মোরগ পোলাও ও বিয়ে বাড়ির রোস্ট',
            'sku' => 'POL-003',
            'barcode' => '8940001003',
            'image' => '/images/food/morog_polao.jpg',
            'description' => 'Fragrant polao rice served with rich chicken roast and egg.',
            'cost_price' => 180.00,
            'selling_price' => 320.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'main_kitchen',
            'has_variants' => false,
            'is_featured' => true,
        ]);

        $itemKebab = Item::create([
            'category_id' => $catKebab->id,
            'branch_id' => $branch->id,
            'name' => 'Chicken Reshmi Kebab (6 pcs)',
            'bangla_name' => 'চিকেন রেশমি কাবাব',
            'sku' => 'KEB-001',
            'barcode' => '8940002001',
            'image' => '/images/food/reshmi_kebab.jpg',
            'description' => 'Melt-in-mouth creamy chicken skewers grilled over charcoal.',
            'cost_price' => 190.00,
            'selling_price' => 350.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'grill',
            'has_variants' => false,
            'is_featured' => true,
        ]);
        $itemKebab->modifiers()->attach([$modExtraCheese->id, $modLessSpicy->id, $modExtraSpicy->id]);

        $itemKalaBhuna = Item::create([
            'category_id' => $catCurry->id,
            'branch_id' => $branch->id,
            'name' => 'Chittagong Beef Kala Bhuna',
            'bangla_name' => 'চট্টগ্রামের ঐতিহ্যবাহী বিফ কালা ভুনা',
            'sku' => 'CUR-001',
            'barcode' => '8940003001',
            'image' => '/images/food/kala_bhuna.jpg',
            'description' => 'Slow-cooked deep caramelized beef loaded with authentic Chittagong spices.',
            'cost_price' => 300.00,
            'selling_price' => 490.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'main_kitchen',
            'has_variants' => false,
            'is_featured' => true,
        ]);

        $itemButterNaan = Item::create([
            'category_id' => $catCurry->id,
            'branch_id' => $branch->id,
            'name' => 'Butter Naan',
            'bangla_name' => 'বাটার নান',
            'sku' => 'NAN-001',
            'image' => '/images/food/butter_naan.jpg',
            'cost_price' => 25.00,
            'selling_price' => 65.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'grill',
            'has_variants' => false,
        ]);

        $itemGarlicNaan = Item::create([
            'category_id' => $catCurry->id,
            'branch_id' => $branch->id,
            'name' => 'Garlic Naan',
            'bangla_name' => 'গার্লিক নান',
            'sku' => 'NAN-002',
            'image' => '/images/food/garlic_naan.jpg',
            'cost_price' => 30.00,
            'selling_price' => 85.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'grill',
            'has_variants' => false,
        ]);

        $itemBorhani = Item::create([
            'category_id' => $catDrinks->id,
            'branch_id' => $branch->id,
            'name' => 'Special Shahi Borhani (250ml)',
            'bangla_name' => 'স্পেশাল শাহী বোরহানি',
            'sku' => 'DRK-001',
            'image' => '/images/food/borhani.jpg',
            'cost_price' => 40.00,
            'selling_price' => 90.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'drinks_bar',
            'has_variants' => false,
            'is_featured' => true,
        ]);

        $itemFirni = Item::create([
            'category_id' => $catDrinks->id,
            'branch_id' => $branch->id,
            'name' => 'Zafrani Shahi Firni (Matka)',
            'bangla_name' => 'জাফরানি শাহী ফিরনি',
            'sku' => 'DES-001',
            'image' => '/images/food/firni.jpg',
            'cost_price' => 35.00,
            'selling_price' => 80.00,
            'vat_percent' => 5.00,
            'kitchen_station' => 'dessert',
            'has_variants' => false,
        ]);

        // 5. Raw Ingredients (কাঁচামাল)
        $ingRice = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Basmati Rice (বাসমতি চাল)', 'unit' => 'kg', 'image' => '/images/ingredients/rice.jpg', 'current_stock' => 85.500, 'alert_stock' => 15.000, 'cost_per_unit' => 140.00]);
        $ingMutton = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Fresh Mutton (খাসির মাংস)', 'unit' => 'kg', 'image' => '/images/ingredients/mutton.jpg', 'current_stock' => 42.000, 'alert_stock' => 10.000, 'cost_per_unit' => 1100.00]);
        $ingChicken = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Broiler Chicken (মুরগির মাংস)', 'unit' => 'kg', 'image' => '/images/ingredients/chicken.jpg', 'current_stock' => 55.000, 'alert_stock' => 15.000, 'cost_per_unit' => 220.00]);
        $ingBeef = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Boneless Beef (গরুর মাংস)', 'unit' => 'kg', 'image' => '/images/ingredients/beef.jpg', 'current_stock' => 38.000, 'alert_stock' => 10.000, 'cost_per_unit' => 800.00]);
        $ingGhee = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Pure Ghee (গাওয়া ঘি)', 'unit' => 'kg', 'image' => '/images/ingredients/ghee.jpg', 'current_stock' => 18.200, 'alert_stock' => 5.000, 'cost_per_unit' => 1200.00]);
        $ingMustardOil = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Mustard Oil (সরিষার তেল)', 'unit' => 'litre', 'image' => '/images/ingredients/mustard_oil.jpg', 'current_stock' => 25.000, 'alert_stock' => 5.000, 'cost_per_unit' => 280.00]);
        $ingYogurt = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Sour Curd / Yogurt (টক দই)', 'unit' => 'kg', 'image' => '/images/ingredients/yogurt.jpg', 'current_stock' => 30.000, 'alert_stock' => 8.000, 'cost_per_unit' => 180.00]);
        $ingFlour = Ingredient::create(['branch_id' => $branch->id, 'name' => 'Maida Flour (ময়দা)', 'unit' => 'kg', 'image' => '/images/ingredients/flour.jpg', 'current_stock' => 60.000, 'alert_stock' => 15.000, 'cost_per_unit' => 65.00]);

        // 6. Recipe BOM
        ItemRecipe::create(['item_id' => $itemKacchi->id, 'variant_id' => $varKacchiFull->id, 'ingredient_id' => $ingRice->id, 'quantity_required' => 0.250]);
        ItemRecipe::create(['item_id' => $itemKacchi->id, 'variant_id' => $varKacchiFull->id, 'ingredient_id' => $ingMutton->id, 'quantity_required' => 0.300]);
        ItemRecipe::create(['item_id' => $itemKacchi->id, 'variant_id' => $varKacchiFull->id, 'ingredient_id' => $ingGhee->id, 'quantity_required' => 0.030]);

        ItemRecipe::create(['item_id' => $itemTehari->id, 'variant_id' => $varTehariFull->id, 'ingredient_id' => $ingRice->id, 'quantity_required' => 0.220]);
        ItemRecipe::create(['item_id' => $itemTehari->id, 'variant_id' => $varTehariFull->id, 'ingredient_id' => $ingBeef->id, 'quantity_required' => 0.250]);
        ItemRecipe::create(['item_id' => $itemTehari->id, 'variant_id' => $varTehariFull->id, 'ingredient_id' => $ingMustardOil->id, 'quantity_required' => 0.035]);

        ItemRecipe::create(['item_id' => $itemButterNaan->id, 'ingredient_id' => $ingFlour->id, 'quantity_required' => 0.150]);
        ItemRecipe::create(['item_id' => $itemButterNaan->id, 'ingredient_id' => $ingGhee->id, 'quantity_required' => 0.020]);

        ItemRecipe::create(['item_id' => $itemBorhani->id, 'ingredient_id' => $ingYogurt->id, 'quantity_required' => 0.250]);

        // 7. Tables across floors
        $floorTables = [
            ['floor' => 'Ground Floor (মেইন হল)', 'name' => 'T-01', 'cap' => 4],
            ['floor' => 'Ground Floor (মেইন হল)', 'name' => 'T-02', 'cap' => 4],
            ['floor' => 'Ground Floor (মেইন হল)', 'name' => 'T-03', 'cap' => 6],
            ['floor' => 'Ground Floor (মেইন হল)', 'name' => 'T-04', 'cap' => 2],
            ['floor' => 'Ground Floor (মেইন হল)', 'name' => 'T-05', 'cap' => 8],
            ['floor' => '1st Floor (এসি লাউঞ্জ)', 'name' => 'AC-01', 'cap' => 4],
            ['floor' => '1st Floor (এসি লাউঞ্জ)', 'name' => 'AC-02', 'cap' => 4],
            ['floor' => '1st Floor (এসি লাউঞ্জ)', 'name' => 'AC-03', 'cap' => 6],
            ['floor' => '1st Floor (এসি লাউঞ্জ)', 'name' => 'AC-04', 'cap' => 6],
            ['floor' => 'VIP Lounge (ভিআইপি রুম)', 'name' => 'VIP-1', 'cap' => 10],
            ['floor' => 'VIP Lounge (ভিআইপি রুম)', 'name' => 'VIP-2', 'cap' => 12],
            ['floor' => 'Rooftop (রুফটপ গার্ডেন)', 'name' => 'RT-01', 'cap' => 4],
            ['floor' => 'Rooftop (রুফটপ গার্ডেন)', 'name' => 'RT-02', 'cap' => 4],
        ];

        foreach ($floorTables as $idx => $t) {
            RestaurantTable::create([
                'branch_id' => $branch->id,
                'floor_name' => $t['floor'],
                'name' => $t['name'],
                'capacity' => $t['cap'],
                'status' => 'available',
                'sort_order' => $idx + 1,
            ]);
        }

        // 8. Open default shift
        Shift::create([
            'branch_id' => $branch->id,
            'user_id' => $cashier->id,
            'opened_at' => now()->subHours(2),
            'opening_float' => 2500.00,
            'expected_cash' => 2500.00,
            'status' => 'open',
        ]);
    }
}
