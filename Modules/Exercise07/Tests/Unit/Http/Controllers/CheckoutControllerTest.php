<?php

namespace Modules\Exercise07\Tests\Unit\Http\Controllers;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Modules\Exercise07\Services\CheckoutService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Modules\Exercise07\Http\Requests\CheckoutRequest;
use Modules\Exercise07\Http\Controllers\CheckoutController;

class CheckoutControllerTest extends TestCase
{
    protected $checkoutServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->checkoutServiceMock = m::mock(CheckoutService::class);
    }

    public function test_index_returns_view()
    {
        $controller = new CheckoutController($this->checkoutServiceMock);

        $view = $controller->index();

        $this->assertEquals('exercise07::checkout.index', $view->getName());
    }

    public function test_it_store_with_only_amount()
    {
        $controller = new CheckoutController($this->checkoutServiceMock);

        $data = [
            'amount' => 1000
        ];

        $request = new CheckoutRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $this->checkoutServiceMock->shouldReceive('calculateShippingFee')->andReturn([
            'amount' => 1000,
            'shipping_fee' => 2000
        ]);

        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('shipping_fee', $response->getSession()->get('order'));
    }
}
