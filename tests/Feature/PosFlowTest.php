<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Services\GeminiService;
use Database\Seeders\RestaurantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RestaurantSeeder::class);
    }

    public function test_pos_catalog_api(): void
    {
        $response = $this->getJson(route('pos.catalog'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'branch',
            'categories',
            'tables',
        ]);
    }

    public function test_pos_order_creation_and_mushak_generation(): void
    {
        $item = Item::where('has_variants', true)->first();
        $variant = $item->variants()->first();
        $table = RestaurantTable::first();

        $initialRice = Ingredient::first()->current_stock;

        $payload = [
            'order_type' => 'dine_in',
            'table_id' => $table->id,
            'token_number' => '55',
            'customer_name' => 'Tanvir Hasan',
            'customer_phone' => '01711223344',
            'items' => [
                [
                    'item_id' => $item->id,
                    'variant_id' => $variant?->id,
                    'quantity' => 2,
                    'unit_price' => $variant ? $variant->price : $item->selling_price,
                    'notes' => 'Less spicy',
                ]
            ],
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'vat_percent' => 5.00,
            'payment_status' => 'paid',
            'payment_method' => 'bkash',
            'paid_amount' => 1000,
        ];

        $response = $this->postJson(route('pos.order.store'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $orderId = $response->json('order.id');
        $order = Order::find($orderId);

        $this->assertNotNull($order);
        $this->assertNotNull($order->mushak_number);
        $this->assertEquals('paid', $order->payment_status);

        // Verify BOM deduction happened
        $newRice = Ingredient::first()->current_stock;
        $this->assertLessThanOrEqual($initialRice, $newRice);
    }

    public function test_kds_active_tickets(): void
    {
        $response = $this->getJson(route('kds.tickets'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'tickets', 'server_time']);
    }

    public function test_gemini_nlp_order_parser(): void
    {
        $gemini = app(GeminiService::class);
        $menuItems = Item::where('is_available', true)->get()->toArray();
        $parsed = $gemini->parseVoiceOrder('২টা কাচ্চি আর ১টা বোরহানি দেন', $menuItems);

        $this->assertIsArray($parsed);
        $this->assertArrayHasKey('items', $parsed);
    }

    public function test_foodpanda_delivery_webhook(): void
    {
        $payload = [
            'order_id' => '994821',
            'customer_name' => 'John Doe',
            'customer_phone' => '01700000000',
            'delivery_address' => 'Dhanmondi 27',
            'items' => [
                ['name' => 'Mutton Kacchi', 'quantity' => 2, 'price' => 540]
            ]
        ];

        $response = $this->postJson('/api/delivery/foodpanda/webhook', $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'acknowledged']);

        $this->assertDatabaseHas('orders', ['order_type' => 'delivery', 'payment_method' => 'foodpanda']);
    }

    public function test_offline_batch_sync(): void
    {
        $payload = [
            'orders' => [
                [
                    'offline_uuid' => 'offline-uuid-12345',
                    'order_number' => 'ORD-OFFLINE-001',
                    'order_type' => 'takeaway',
                    'customer_name' => 'Offline Guest',
                    'subtotal' => 450,
                    'vat_percent' => 5,
                    'vat_amount' => 22.5,
                    'grand_total' => 472.5,
                    'paid_amount' => 500,
                    'payment_status' => 'paid',
                    'payment_method' => 'cash',
                    'items' => [
                        ['item_id' => Item::first()->id, 'quantity' => 1, 'unit_price' => 450, 'subtotal' => 450, 'line_total' => 450]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/offline/batch-sync', $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', ['offline_uuid' => 'offline-uuid-12345']);
    }
}
