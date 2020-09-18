<?php

namespace Modules\Exercise03\Tests\Repositories;

use Tests\TestCase;
use Modules\Exercise03\Entities\Product;
use Modules\Exercise03\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    /**
     * @var ProductRepository
     */
    protected $eloquentProductRepository;

    /**
     * @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|Product
     */
    private $mockedProductModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedProductModel = \Mockery::mock(Product::class);
        $this->eloquentProductRepository = new ProductRepository(
            $this->mockedProductModel
        );
    }

    public function test_function_all()
    {
        $expected = ['foo' => 'bar'];
        $this->mockedProductModel->shouldReceive('all')->andReturn($expected);

        $this->assertEquals($expected, $this->eloquentProductRepository->all());
    }
}
