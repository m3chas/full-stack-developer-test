<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    /**
     * Get vehicle type of vehicle plate number
     *
     * @return void
     */
    public function testGetVehicleType()
    {

        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $response = $this->postJson('/api/vehicles/type', $parameters);
        $response->assertStatus(200);
    }

    /**
     * Add a new vehicle on the system.
     *
     * @return void
     */
    public function testCreateVehicle()
    {

        $parameters = [
            'plate_number' => 'TEST123TEST',
            'type' => 1
        ];

        $response = $this->postJson('/api/vehicles/create', $parameters);
        $response->assertStatus(200);
    }

    /**
     * Add a new vehicle on the system.
     *
     * @return void
     */
    public function testUpdateVehicleMinutes()
    {

        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $response = $this->postJson('/api/vehicles/minutes', $parameters);
        $response->assertStatus(200);
    }
}