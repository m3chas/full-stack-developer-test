<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CheckingTest extends TestCase
{
    /**
     * Test a new car checking log in Parking Logs.
     *
     * @return void
     */
    public function testShouldRegisterACheckingLog()
    {
        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $this->post('checking', $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'status',
                    'plate_number',
                    'checking',
                ]
            ]    
        );
    }

    /**
     * Test a new car checking log in Parking Logs.
     *
     * @return void
     */
    public function testShouldRegisterACheckoutLog()
    {
        $parameters = [
            'plate_number' => 'TEST123TEST',
        ];

        $this->post('checkout', $parameters);
        $this->seeStatusCode(200);
    }
}
