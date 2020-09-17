<?php

namespace Modules\Exercise03\Tests\Services;

use Tests\TestCase;
use InvalidArgumentException;
use Modules\Exercise03\Entities\Product;
use Modules\Exercise03\Services\ProductService;

/**
 * Class ProductServiceTest
 * @package Modules\Exercise03\Tests\Unit\Services
 */
class ProductServiceTest extends TestCase
{
    /**
     * @var ProductService
     */
    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = new ProductService();
    }

    public function test_it_throw_exception_when_number_product_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->productService->calculateDiscount([
            Product::CRAVAT_TYPE => -1,
            Product::WHITE_SHIRT_TYPE => -1,
            Product::OTHER_TYPE => -1,
        ]);
    }

    /**
     * @param $passedParams
     * @param $expectedDiscount
     * @dataProvider providerTestCalculateExercise1
     * @dataProvider providerTestCalculateExercise2
     */
    public function test_calculate_discount($passedParams, $expectedDiscount)
    {
        $result = $this->productService->calculateDiscount($passedParams);

        $this->assertEquals($result, $expectedDiscount);
    }

    /**
     * Data provider for exercise 1
     * @return array
     */
    public function providerTestCalculateExercise1()
    {
        return [
            /**
             * TC1
             * total > 7
             * have cravat
             * have white shirt
             */
            [
                [
                    Product::CRAVAT_TYPE => 7,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                12,
            ],
            /**
             * TC2
             * total > 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 8,
                ],
                7,
            ],
            /**
             * TC3
             * total > 7
             * have only cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 8,
                ],
                7,
            ],
            /**
             * TC4
             * total <= 7
             * have both white shirt and cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 1,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                5,
            ],
            /**
             * TC5
             * total <= 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                0,
            ],
            /**
             * TC6
             * total <= 7
             * have no cravat or white shirt
             */
            [
                [
                    Product::OTHER_TYPE => 1,
                ],
                0,
            ],
        ];
    }


    /**
     * Data provider for exercise 2
     *
     * @return array
     */
    public function providerTestCalculateExercise2()
    {
        return [
            /**
             * TC1
             * total > 7
             * have cravat
             * have white shirt
             */
            [
                [
                    Product::CRAVAT_TYPE => 7,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                12,
            ],
            /**
             * TC2
             * total > 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 8,
                ],
                7,
            ],
            /**
             * TC3
             * total > 7
             * have only cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 8,
                ],
                7,
            ],
            /**
             * TC4
             * total > 7
             * have no cravat and white shirt
             */
            [
                [
                    Product::OTHER_TYPE => 8,
                ],
                7,
            ],
            /**
             * TC5
             * total > 7
             * have both white shirt and cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 1,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                5,
            ],
            /**
             * TC6
             * total <= 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                0,
            ],
            /**
             * TC7
             * total <= 7
             * have no cravat or white shirt
             */
            [
                [
                    Product::CRAVAT_TYPE => 1,
                ],
                0,
            ],
            /**
             * TC8
             * total <= 7
             * have no cravat or white shirt
             */
            [
                [
                    Product::OTHER_TYPE => 1,
                ],
                0,
            ],
        ];
    }
}
