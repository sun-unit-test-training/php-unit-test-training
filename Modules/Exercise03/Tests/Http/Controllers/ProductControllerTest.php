<?php

namespace Modules\Exercise03\Tests\Http\Controllers;

use Tests\TestCase;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Modules\Exercise03\Services\ProductService;
use Modules\Exercise03\Http\Requests\CheckoutRequest;
use Modules\Exercise03\Http\Controllers\ProductController;
use Modules\Exercise03\Repositories\EloquentProductRepository;

/**
 * TODO: make real test
 */
class ProductControllerTest extends TestCase
{
    /**
     * @var ProductController
     */
    protected $productController;

    /**
     * @var \Mockery\MockInterface
     */
    protected $productService;

    /**
     * @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|EloquentProductRepository
     */
    protected $productRepository;

    protected function setUp(): void
    {
        parent::setup();

        $this->productService = \Mockery::mock(ProductService::class);
        $this->productRepository = \Mockery::mock(EloquentProductRepository::class);
        $this->productController = new ProductController(
            $this->productService,
            $this->productRepository
        );
    }

    public function test_function_checkout()
    {
        $mockedRequest = \Mockery::mock(CheckoutRequest::class);
        $mockedRequest->shouldReceive('input')->andReturn([]);
        $discount = 1;
        $this->productService->shouldReceive('calculateDiscount')->andReturn($discount);
        $response = $this->productController->checkout($mockedRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['discount' => $discount], $response->getOriginalContent());
    }

    public function test_function_index()
    {
        $products = ['foo' => 'bar'];
        $this->productRepository->shouldReceive('all')->andReturn($products);
        $response = $this->productController->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('exercise03::index', $response->getName());
        $this->assertEquals(compact('products'), $response->getData());
    }
}
