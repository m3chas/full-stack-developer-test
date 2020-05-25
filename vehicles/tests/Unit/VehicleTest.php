<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldGetVehicleType()
    {
        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $this->post('type', $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'type'
                ]
            ]    
        );
    }
}
