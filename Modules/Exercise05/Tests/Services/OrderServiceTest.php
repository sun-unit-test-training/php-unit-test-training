<?php

namespace Modules\Exercise05\Tests\Services;

use Carbon\Carbon;
use InvalidArgumentException;
use Modules\Exercise05\Services\OrderService;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function test_has_discount_potato_and_receive_at_home_and_has_coupon()
    {
        $orderDetail = [
            'price' => 1501,
            'option_receive' => config('exercise05.receive_at_home'),
            'option_coupon' => config('exercise05.has_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertEquals(config('exercise05.free_potato'), $discount['discount_potato']);
        $this->assertEquals(($orderDetail['price'] * 80) / 100, $discount['price']);
        $this->assertNull($discount['discount_pizza']);
    }

    public function test_has_discount_potato_and_receive_at_home_and_no_coupon()
    {
        $orderDetail = [
            'price' => 1501,
            'option_receive' => config('exercise05.receive_at_home'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertEquals(config('exercise05.free_potato'), $discount['discount_potato']);
        $this->assertEquals($orderDetail['price'], $discount['price']);
        $this->assertNull($discount['discount_pizza']);
    }

    public function test_has_discount_potato_and_receive_at_store()
    {
        $orderDetail = [
            'price' => 1501,
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertEquals(config('exercise05.free_potato'), $discount['discount_potato']);
        $this->assertEquals($orderDetail['price'], $discount['price']);
        $this->assertEquals(config('exercise05.discount_pizza'), $discount['discount_pizza']);
    }

    public function test_no_discount_potato_and_receive_at_home_and_has_coupon()
    {
        $orderDetail = [
            'price' => 1500,
            'option_receive' => config('exercise05.receive_at_home'),
            'option_coupon' => config('exercise05.has_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertNull($discount['discount_potato']);
        $this->assertEquals(($orderDetail['price'] * 80) / 100, $discount['price']);
        $this->assertNull($discount['discount_pizza']);
    }

    public function test_no_discount_potato_and_receive_at_home_and_no_coupon()
    {
        $orderDetail = [
            'price' => 1499,
            'option_receive' => config('exercise05.receive_at_home'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertNull($discount['discount_potato']);
        $this->assertEquals($orderDetail['price'], $discount['price']);
        $this->assertNull($discount['discount_pizza']);
    }

    public function test_no_discount_potato_and_receive_store()
    {
        $orderDetail = [
            'price' => 1499,
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];
        $orderService = new OrderService();
        $discount = $orderService->handleDiscount($orderDetail);

        $this->assertEquals(config('exercise05.discount_pizza'), $discount['discount_pizza']);
        $this->assertEquals($orderDetail['price'], $discount['price']);
        $this->assertNull($discount['discount_potato']);
    }
}
