<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class POSTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::create([
            'name' => 'POS Shop',
            'slug' => 'pos-shop',
            'subscription_ends_at' => now()->addMonth(),
            'plan_id' => Plan::create(['name' => 'Pro', 'device_limit' => 10, 'price' => 10])->id
        ]);

        $this->user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        
        $this->category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Drinks'
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Pepsi',
            'price' => 5.00,
            'stock' => 100
        ]);
    }

    public function test_can_create_order()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/orders", [
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity' => 2
                    ]
                ]
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.total_price', "10.00");

        $this->assertDatabaseHas('orders', [
            'total_price' => 10.00,
            'status' => 'pending'
        ]);
    }
}
