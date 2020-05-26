<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetVehicleType()
    {

        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $response = $this->postJson('/api/type', $parameters);
        $response
            ->assertStatus(200)
            ->assertJson(['data' =>
                [
                    'type',
                ]
            ]);
    }
}