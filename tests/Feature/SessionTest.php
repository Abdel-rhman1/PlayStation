<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Enums\DeviceStatus;
use App\Services\IoT\DeviceControlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class SessionTest extends TestCase
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
            'status' => DeviceStatus::OFF,
        ]);
    }

    public function test_can_start_session()
    {
        $mock = Mockery::mock(DeviceControlService::class);
        $mock->shouldReceive('turnOn')->once()->andReturn(true);
        $this->app->instance(DeviceControlService::class, $mock);

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/devices/{$this->device->id}/start");

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'active');

        $this->assertEquals(DeviceStatus::IN_USE, $this->device->fresh()->status);
    }

    public function test_cannot_start_session_if_device_is_already_in_use()
    {
        $this->device->update(['status' => DeviceStatus::IN_USE]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/devices/{$this->device->id}/start");

        $response->assertStatus(400);
    }

    public function test_can_stop_session_and_calculate_cost()
    {
        // Start session manually
        $session = \App\Models\Session::create([
            'tenant_id' => $this->tenant->id,
            'device_id' => $this->device->id,
            'started_at' => now()->subHours(2),
            'status' => 'active',
        ]);
        $this->device->update(['status' => DeviceStatus::IN_USE]);

        $mock = Mockery::mock(DeviceControlService::class);
        $mock->shouldReceive('turnOff')->once()->andReturn(true);
        $this->app->instance(DeviceControlService::class, $mock);

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/devices/{$this->device->id}/stop");

        $response->assertStatus(200);
        
        $this->assertEquals(20.00, $response->json('data.cost')); // 2 hours * 10/hr
        $this->assertEquals(DeviceStatus::OFF, $this->device->fresh()->status);
    }
}
