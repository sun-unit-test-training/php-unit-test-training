<?php

namespace Modules\Exercise01\Tests\Unit\Services;

use Carbon\Carbon;
use InvalidArgumentException;
use Modules\Exercise01\Services\PriceService;
use Tests\TestCase;

class PriceServiceTest extends TestCase
{
    function test_it_throw_exception_when_order_negative_cup()
    {
        $this->expectException(InvalidArgumentException::class);
        $priceService = new PriceService;
        $priceService->calculate(-1, false);
    }

    function test_it_throw_exception_when_order_0_cup()
    {
        $this->expectException(InvalidArgumentException::class);
        $priceService = new PriceService;
        $priceService->calculate(0, false);
    }

    /**
     * @dataProvider provideSpecialTime
     *
     * C0 test for PriceService, 100% coverage, but if we change
     * ```diff
     * - $voucherDiscount = 0;
     * + $voucherDiscount = 10;
     * ```
     * => Test still pass, but wrong logic for the case not use voucher (*1)
     */
    function test_it_create_order_when_input_many_cups_with_voucher_in_special_time($orderDate)
    {
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 5;
        $expectedTotal = 1 * $priceService::VOUCHER_UNIT_PRICE + 4 * $priceService::SPECIAL_TIME_UNIT_PRICE;

        $price = $priceService->calculate($quantity, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
        $this->assertEquals(
            $priceService::UNIT_PRICE - $priceService::VOUCHER_UNIT_PRICE,
            $price->getVoucherDiscount()
        );
        $this->assertEquals(4 * (
            $priceService::UNIT_PRICE - $priceService::SPECIAL_TIME_UNIT_PRICE
        ), $price->getSpecialTimeDiscount());
    }

    function provideSpecialTime()
    {
        return [
            [Carbon::parse('2020-09-15 16:00')],
            [Carbon::parse('2020-09-15 16:01')],
            [Carbon::parse('2020-09-15 17:59')],
            [Carbon::parse('2020-09-15 17:58')],
        ];
    }

    /**
     * Test case to cover (*1)
     * But if we change
     * ```diff
     * - $specialTimeDiscount = 0;
     * + $specialTimeDiscount = 10;
     * ```
     * => Test still pass, but wrong logic for the case not in special time (*2)
     */
    function test_it_create_order_when_input_many_cups_no_voucher_in_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 16:10');
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 8;
        $expectedTotal = $quantity * $priceService::SPECIAL_TIME_UNIT_PRICE;

        $price = $priceService->calculate($quantity, false);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    /**
     * Test case to cover (*2)
     */
    function test_it_create_order_when_input_many_cups_no_voucher_no_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 15:30');
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 10;
        $expectedTotal = $quantity * $priceService::UNIT_PRICE;

        $price = $priceService->calculate($quantity, false);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    /**
     * @dataProvider provideNoSpecialTime
     *
     * Test case to ensure voucher does not depends on special time
     */
    function test_it_create_order_when_input_many_cups_with_voucher_no_special_time($orderDate)
    {
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 5;
        $expectedTotal = 1 * $priceService::VOUCHER_UNIT_PRICE + 4 * $priceService::UNIT_PRICE;

        $price = $priceService->calculate($quantity, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    function provideNoSpecialTime()
    {
        return [
            [Carbon::parse('2020-09-15 15:59')],
            [Carbon::parse('2020-09-15 18:00')],
        ];
    }

    function test_it_create_order_when_input_1_cup_no_voucher_no_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 18:00');
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 1;
        $expectedTotal = $quantity * $priceService::UNIT_PRICE;

        $price = $priceService->calculate($quantity, false);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    function test_it_create_order_when_input_1_cup_no_voucher_in_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 16:00');
        Carbon::setTestNow($orderDate);
        $priceService = new PriceService;
        $quantity = 1;
        $expectedTotal = $quantity * $priceService::SPECIAL_TIME_UNIT_PRICE;

        $price = $priceService->calculate($quantity, false);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }
}
