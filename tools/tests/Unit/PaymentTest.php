<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PaymentTest extends TestCase
{
    /**
     * Generate report for resident payments.
     *
     * @return void
     */
    public function testGeneratePaymentsReport()
    {

        $response = $this->getJson('/api/payments');
        $response->assertStatus(200);
    }

    /**
     * Generate report for resident payments.
     *
     * @return void
     */
    public function testNewMonth()
    {

        $response = $this->getJson('/api/new');
        $response->assertStatus(200);
    }
}
