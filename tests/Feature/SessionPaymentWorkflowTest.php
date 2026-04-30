<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Session;
use App\Models\Order;
use App\Enums\DeviceStatus;
use App\Services\IoT\DeviceControlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class SessionPaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->plan = Plan::create([
            'name' => 'Pro',
            'device_limit' => 10,
            'price' => 100,
        ]);

        $this->tenant = Tenant::create([
            'name' => 'Test Shop',
            'slug' => 'test-shop',
            'plan_id' => $this->plan->id,
            'subscription_ends_at' => now()->addMonth(),
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $this->device = Device::factory()->create([
            'tenant_id' => $this->tenant->id,
            'hourly_rate' => 10.00,
            'fixed_rate' => 0,
            'status' => DeviceStatus::OFF,
        ]);

        $this->product = Product::factory()->create([
            'tenant_id' => $this->tenant->id,
            'price' => 5.00,
        ]);
    }

    public function test_full_session_payment_workflow()
    {
        // 1. Start session
        $mock = Mockery::mock(DeviceControlService::class);
        $mock->shouldReceive('turnOn')->andReturn(true);
        $mock->shouldReceive('turnOff')->andReturn(true);
        $this->app->instance(DeviceControlService::class, $mock);

        $this->actingAs($this->user)
            ->post("/sessions/start/{$this->device->id}");

        $session = Session::where('device_id', $this->device->id)->where('status', 'active')->first();
        $this->assertNotNull($session);

        // 2. Add orders (2 orders)
        $posService = app(\App\Domains\POS\Services\POSService::class);
        
        // Order 1: 10.00 (Unpaid)
        $order1 = $posService->createOrder($this->user->id, [
            ['product_id' => $this->product->id, 'quantity' => 2]
        ], $this->device->id, false);

        // Order 2: 5.00 (Unpaid)
        $order2 = $posService->createOrder($this->user->id, [
            ['product_id' => $this->product->id, 'quantity' => 1]
        ], $this->device->id, false);

        // 3. Pay Order 1 instantly
        $response = $this->actingAs($this->user)
            ->postJson("/orders/{$order1->id}/pay");
            
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals('paid', $order1->fresh()->payment_status);

        // 4. Verify Real-time totals
        $totals = $session->fresh()->real_time_totals;
        $this->assertEquals(10.00, (float)$totals['paid_orders_total']);
        $this->assertEquals(5.00, (float)$totals['unpaid_orders_total']);

        // 5. Stop session after some time
        // Simulate time passage by manually setting started_at
        $session->update(['started_at' => now()->subHours(2)]);
        
        $this->actingAs($this->user)
            ->post("/sessions/stop/{$this->device->id}");

        $session->refresh();
        $this->assertEquals('completed', $session->status);
        $this->assertEquals(20.00, (float)$session->cost); // 2 hours * 10
        
        // 6. Verify Receipt Data
        $billingService = app(\App\Services\Sessions\SessionBillingService::class);
        $receipt = $billingService->generateReceiptData($session);

        $this->assertEquals(10.00, (float)$receipt['paid_orders']['total']);
        $this->assertEquals(5.00, (float)$receipt['unpaid_orders']['total']);
        $this->assertEquals(25.00, (float)$receipt['due_amount']); // 20 (device) + 5 (unpaid)
        $this->assertEquals(35.00, (float)$receipt['grand_total']); // 20 + 10 + 5
    }
}
