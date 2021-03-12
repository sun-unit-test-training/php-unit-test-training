<?php

namespace Modules\Exercise07\Tests\Unit\Services;

use Tests\TestCase;
use Modules\Exercise07\Services\CheckoutService;

class CheckoutServiceTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CheckoutService();
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_all_condition()
    {
        $orderInput = [
            'amount' => 6000,
            'premium_member' => true,
            'shipping_express' => true,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(500, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_without_shipping_express()
    {
        $orderInput = [
            'amount' => 6000,
            'premium_member' => true,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(0, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_amount_is_less_than_5000()
    {
        $orderInput = [
            'amount' => 4000,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(500, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_premium_member()
    {
        $orderInput = [
            'amount' => 6000,
            'premium_member' => true,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(0, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_without_premium_member()
    {
        $orderInput = [
            'amount' => 6000,
            'shipping_express' => true,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(500, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_amount_is_higher_than_5000()
    {
        $orderInput = [
            'amount' => 6000,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(0, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_amount_is_5000()
    {
        $orderInput = [
            'amount' => 5000,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(0, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_shipping_express()
    {
        $orderInput = [
            'shipping_express' => true,
            'amount' => 4000,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(1000, $result['shipping_fee']);
    }

    /**
     * @test
     */
    public function calculate_shipping_fee_with_normal_case()
    {
        $orderInput = [
            'amount' => 4000,
        ];

        $result = $this->service->calculateShippingFee($orderInput);
        $this->assertArrayHasKey('shipping_fee', $result);
        $this->assertEquals(500, $result['shipping_fee']);
    }
}
