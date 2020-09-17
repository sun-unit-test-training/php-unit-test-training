<?php

namespace Modules\Exercise01\Tests\Services;

use Carbon\Carbon;
use InvalidArgumentException;
use Modules\Exercise01\Services\PriceService;
use Tests\TestCase;

class PriceServiceTest extends TestCase
{
    public function test_it_throw_exception_when_order_negative_cup()
    {
        $this->expectException(InvalidArgumentException::class);
        $priceService = new PriceService;
        $priceService->calculate(-1, false);
    }

    public function test_it_throw_exception_when_order_0_cup()
    {
        $this->expectException(InvalidArgumentException::class);
        $priceService = new PriceService;
        $priceService->calculate(0, false);
    }

    public function test_it_calculate_price_when_order_1_cup_with_voucher_no_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime . ' -30 minutes');
        Carbon::setTestNow($orderDate);

        $price = $priceService->calculate(1, true);

        $this->assertEquals($priceService::VOUCHER_UNIT_PRICE, $price->getTotal());
        $this->assertEquals(
            $priceService::UNIT_PRICE - $priceService::VOUCHER_UNIT_PRICE,
            $price->getVoucherDiscount()
        );
        $this->assertEquals(0, $price->getSpecialTimeDiscount());
    }

    public function test_it_calculate_price_when_order_1_cup_no_voucher_no_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime . ' -30 minutes');
        Carbon::setTestNow($orderDate);

        $price = $priceService->calculate(1, false);

        $this->assertEquals($priceService::UNIT_PRICE, $price->getTotal());
        $this->assertEquals(0, $price->getVoucherDiscount());
        $this->assertEquals(0, $price->getSpecialTimeDiscount());
    }

    public function test_it_calculate_price_when_order_1_cup_with_voucher_in_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime);
        Carbon::setTestNow($orderDate);

        $price = $priceService->calculate(1, true);

        $this->assertEquals($priceService::VOUCHER_UNIT_PRICE, $price->getTotal());
        $this->assertEquals(
            $priceService::UNIT_PRICE - $priceService::VOUCHER_UNIT_PRICE,
            $price->getVoucherDiscount()
        );
        $this->assertEquals(0, $price->getSpecialTimeDiscount());
    }

    public function test_it_calculate_price_when_order_2_cups_with_voucher_no_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime . ' -30 minutes');
        Carbon::setTestNow($orderDate);
        $expectedTotal = $priceService::VOUCHER_UNIT_PRICE * 1 + $priceService::UNIT_PRICE * 1;

        $price = $priceService->calculate(2, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    public function test_it_calculate_price_when_order_2_cups_with_voucher_in_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime);
        Carbon::setTestNow($orderDate);
        $expectedTotal = $priceService::VOUCHER_UNIT_PRICE * 1 + $priceService::SPECIAL_TIME_UNIT_PRICE * 1;
        $expectedSpecialDiscount = $priceService::UNIT_PRICE - $priceService::SPECIAL_TIME_UNIT_PRICE;

        $price = $priceService->calculate(2, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
        $this->assertEquals($expectedSpecialDiscount, $price->getSpecialTimeDiscount());
    }

    public function test_it_calculate_price_when_order_2_cups_no_voucher_in_special_time()
    {
        $priceService = new PriceService;
        list($minSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;
        $orderDate = Carbon::parse('2020-09-15 ' . $minSpecialTime);
        Carbon::setTestNow($orderDate);

        $price = $priceService->calculate(2, false);

        $this->assertEquals(2 * $priceService::SPECIAL_TIME_UNIT_PRICE, $price->getTotal());
    }

    /**
     * @dataProvider provideSpecialTime
     */
    public function test_it_calculate_price_when_order_many_cups_with_voucher_in_special_time($priceService, $orderDate)
    {
        Carbon::setTestNow($orderDate);
        $quantity = 5;
        $expectedTotal = 1 * $priceService::VOUCHER_UNIT_PRICE + 4 * $priceService::SPECIAL_TIME_UNIT_PRICE;

        $price = $priceService->calculate($quantity, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    public function provideSpecialTime()
    {
        $priceService = new PriceService;
        list($minSpecialTime, $maxSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;

        return [
            [$priceService, Carbon::parse('2020-09-15 ' . $minSpecialTime)],
            [$priceService, Carbon::parse('2020-09-15 ' . $minSpecialTime . '+1 minute')],
            [$priceService, Carbon::parse('2020-09-15 ' . $maxSpecialTime)],
            [$priceService, Carbon::parse('2020-09-15 ' . $maxSpecialTime . '-1 minute')],
        ];
    }

    /**
     * @dataProvider provideNoSpecialTime
     */
    public function test_it_calculate_price_when_order_many_cups_with_voucher_no_special_time($priceService, $orderDate)
    {
        Carbon::setTestNow($orderDate);
        $quantity = 5;
        $expectedTotal = 1 * $priceService::VOUCHER_UNIT_PRICE + 4 * $priceService::UNIT_PRICE;

        $price = $priceService->calculate($quantity, true);

        $this->assertEquals($expectedTotal, $price->getTotal());
    }

    public function provideNoSpecialTime()
    {
        $priceService = new PriceService;
        list($minSpecialTime, $maxSpecialTime) = $priceService::SPECIAL_TIME_PERIOD;

        return [
            [$priceService, Carbon::parse('2020-09-15 ' . $minSpecialTime . '-1 minute')],
            [$priceService, Carbon::parse('2020-09-15 ' . $maxSpecialTime . '+1 minute')],
        ];
    }
}
