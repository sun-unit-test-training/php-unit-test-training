<?php

namespace Modules\Exercise01\Tests\Feature\Http\Controllers;

use Carbon\Carbon;
use Tests\TestCase;
use Modules\Exercise01\Http\Controllers\OrderController;
use Modules\Exercise01\Models\Voucher;
use Modules\Exercise01\Services\DTO\Price;
use Tests\SetupDatabaseTrait;

class OrderControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    function test_it_show_form_order()
    {
        $url = action([OrderController::class, 'showForm']);

        $response = $this->get($url);

        $response->assertViewIs('exercise01::order');
        $response->assertViewHasAll([
            'unitPrice',
            'voucherUnitPrice',
            'specialTimeUnitPrice',
            'specialTimePeriod',
        ]);
        $response->assertSessionMissing('order');
    }

    /**
     * @dataProvider provideWrongQuantity
     */
    function test_it_show_error_when_input_wrong_quantity($quantity)
    {
        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => $quantity,
        ]);

        $response->assertSessionHasErrors(['quantity']);
    }

    function provideWrongQuantity()
    {
        return [
            [null],
            [0],
            [''],
            ['   '],
            ['not-a-number'],
        ];
    }

    /**
     * @dataProvider provideEmptyVoucher
     */
    function test_it_should_not_error_when_input_empty_voucher($voucher)
    {
        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 1,
            'voucher' => $voucher,
        ]);

        $response->assertSessionDoesntHaveErrors(['voucher']);
    }

    function provideEmptyVoucher()
    {
        return [
            [null],
            [''],
            ['   '],
        ];
    }

    function test_it_show_error_when_input_non_existence_voucher_code()
    {
        factory(Voucher::class)->state('active')->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 1,
            'voucher' => 'not-existed-voucher',
        ]);

        $response->assertSessionHasErrors(['voucher']);
        $response->assertSessionDoesntHaveErrors(['quantity']);
    }

    function test_it_show_error_when_input_inactive_voucher_code()
    {
        factory(Voucher::class)->state('inactive')->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 1,
            'voucher' => 'existed-voucher',
        ]);

        $response->assertSessionHasErrors(['voucher']);
    }

    function test_it_create_order_when_input_valid_quantity_and_voucher_code()
    {
        factory(Voucher::class)->state('active')->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 1,
            'voucher' => 'existed-voucher',
        ]);

        $response->assertSessionDoesntHaveErrors(['quantity']);
        $response->assertSessionDoesntHaveErrors(['voucher']);
        $response->assertSessionHasInput(['quantity', 'voucher']);
        $response->assertSessionHas('order', function ($order) {
            return $order['quantity'] == 1 && $order['price'] instanceof Price;
        });
    }

    /**
     * @dataProvider provideSpecialTime
     *
     * C0 test for PriceService, 100% coverage, but if we change
     * - $voucherDiscount = 0;
     * + $voucherDiscount = 10;
     * => Test still pass, but wrong logic for the case not use voucher (*1)
     */
    public function test_it_create_order_when_input_many_cups_with_voucher_in_special_time($orderDate)
    {
        Carbon::setTestNow($orderDate);
        $quantity = 5;
        $expectedTotal = 100 * 1 + 290 * 4;

        factory(Voucher::class)->state('active')->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => $quantity,
            'voucher' => 'existed-voucher',
        ]);

        $response->assertSessionHas('order', function ($order) use ($expectedTotal) {
            return $order['price']->getTotal() === $expectedTotal;
        });
    }

    public function provideSpecialTime()
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
     * - $specialTimeDiscount = 0;
     * + $specialTimeDiscount = 10;
     * => Test still pass, but wrong logic for the case not in special time (*2)
     */
    public function test_it_create_order_when_input_many_cups_no_voucher_in_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 16:10');
        Carbon::setTestNow($orderDate);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 8,
        ]);

        $response->assertSessionHas('order', function ($order) {
            $expectedTotal = 290 * 8;
            return $order['price']->getTotal() === $expectedTotal;
        });
    }

    /**
     * Test case to cover (*2)
     */
    public function test_it_create_order_when_input_many_cups_no_voucher_no_special_time()
    {
        $orderDate = Carbon::parse('2020-09-15 15:30');
        Carbon::setTestNow($orderDate);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 10,
        ]);

        $response->assertSessionHas('order', function ($order) {
            $expectedTotal = 490 * 10;
            return $order['price']->getTotal() === $expectedTotal;
        });
    }

    /**
     * @dataProvider provideNoSpecialTime
     *
     * Test case to ensure voucher does not depends on special time
     */
    public function test_it_create_order_when_input_many_cups_with_voucher_no_special_time($orderDate)
    {
        Carbon::setTestNow($orderDate);
        $quantity = 5;
        $expectedTotal = 100 * 1 + 490 * 4;

        factory(Voucher::class)->state('active')->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => $quantity,
            'voucher' => 'existed-voucher',
        ]);

        $response->assertSessionHas('order', function ($order) use ($expectedTotal) {
            return $order['price']->getTotal() === $expectedTotal;
        });
    }

    public function provideNoSpecialTime()
    {
        return [
            [Carbon::parse('2020-09-15 15:59')],
            [Carbon::parse('2020-09-15 18:00')],
        ];
    }
}
