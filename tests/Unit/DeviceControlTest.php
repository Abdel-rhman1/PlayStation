<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Services\IoT\Drivers\SonoffHttpDriver;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeviceControlTest extends TestCase
{
    public function test_driver_sends_correct_http_payload()
    {
        Http::fake([
            '*' => Http::response(['status' => 'success'], 200),
        ]);

        $device = new Device(['name' => 'PS5-1', 'ip_address' => '192.168.1.10']);
        $driver = new SonoffHttpDriver();

        $result = $driver->turnOn($device);

        $this->assertTrue($result);
        
        Http::assertSent(function ($request) {
            return $request->url() == "http://192.168.1.10:8081/zeroconf/switch" &&
                   $request['data']['switch'] == 'on';
        });
    }

    public function test_driver_handles_http_failure()
    {
        Http::fake([
            '*' => Http::response('Error', 500),
        ]);

        $device = new Device(['name' => 'PS5-1', 'ip_address' => '192.168.1.10']);
        $driver = new SonoffHttpDriver();

        $result = $driver->turnOff($device);

        $this->assertFalse($result);
    }
}
