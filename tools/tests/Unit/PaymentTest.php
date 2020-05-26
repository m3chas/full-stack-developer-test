<?php

namespace Tests\Unit;

use Tests\TestCase;

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
}
