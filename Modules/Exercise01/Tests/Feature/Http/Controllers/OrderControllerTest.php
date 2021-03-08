<?php

namespace Modules\Exercise01\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Modules\Exercise01\Http\Controllers\OrderController;
use Modules\Exercise01\Models\Voucher;
use Modules\Exercise01\Services\DTO\Price;
use Modules\Exercise01\Services\PriceService;
use Tests\SetupDatabaseTrait;

class OrderControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    protected $priceServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Laravel helper: mock and bind to service container
        $this->priceServiceMock = $this->mock(PriceService::class);
    }

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

    function test_it_show_error_when_missing_input_quantity()
    {
        $url = action([OrderController::class, 'create']);

        $response = $this->post($url);

        $response->assertSessionHasErrors(['quantity']);
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
        Voucher::factory()->active()->create(['code' => 'existed-voucher']);

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
        Voucher::factory()->inactive()->create(['code' => 'existed-voucher']);

        $url = action([OrderController::class, 'create']);

        $response = $this->post($url, [
            'quantity' => 1,
            'voucher' => 'existed-voucher',
        ]);

        $response->assertSessionHasErrors(['voucher']);
    }

    function test_it_create_order_when_input_valid_quantity_and_voucher_code()
    {
        Voucher::factory()->active()->create(['code' => 'existed-voucher']);
        $dummyPrice = new Price(100, 0, 0);
        $this->priceServiceMock
            ->shouldReceive('calculate')
            ->andReturn($dummyPrice);

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
}
