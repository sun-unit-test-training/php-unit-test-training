<?php

namespace Modules\Exercise03\Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Tests\SetupDatabaseTrait;
use Modules\Exercise03\Entities\Product;
use Modules\Exercise03\Http\Controllers\ProductController;

class ProductControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    public function test_it_shows_index()
    {
        $url = action([ProductController::class, 'index']);
        $response = $this->get($url);
        $response->assertViewIs('exercise03::index');
        $response->assertViewHasAll(['products']);
    }

    /**
     * @dataProvider provideWrongTotalProducts
     */
    public function test_it_validates_fail_input_wrong_total_products($totalProducts)
    {
        $url = action([ProductController::class, 'checkout']);
        $response = $this->post($url, [
            'total_products' => $totalProducts,
        ]);
        $response->assertSessionHasErrors(['total_products']);
    }

    public function provideWrongTotalProducts()
    {
        return [
            [0],
            [null],
            ['foo'],
        ];
    }

    /**
     * @dataProvider provideWrongTotalProductsItems
     */
    public function test_it_validates_fail_input_wrong_total_products_item($totalProducts)
    {
        $url = action([ProductController::class, 'checkout']);
        $response = $this->post($url, [
            'total_products' => $totalProducts,
        ]);
        $response->assertSessionHasErrors(['total_products.*']);
    }

    public function provideWrongTotalProductsItems()
    {
        return [
            [[1 => -1]],
            [[1 => 'foo']],
        ];
    }

    /**
     * @param $passedParams
     * @param $expectedDiscount
     * @dataProvider providerTestCalculateExercise1
     * @dataProvider providerTestCalculateExercise2
     */
    public function test_it_checkout_successfully($passedParams, $expectedDiscount)
    {
        $url = action([ProductController::class, 'checkout']);
        $response = $this->post($url, [
            'total_products' => $passedParams,
        ]);

        $response->assertJson(['discount' => $expectedDiscount]);
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
             * total >= 7
             * have cravat
             * have white shirt
             */
            [
                [
                    Product::CRAVAT_TYPE => 6,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                12,
            ],
            /**
             * TC2
             * total >= 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 7,
                ],
                7,
            ],
            /**
             * TC3
             * total >= 7
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
             * total < 7
             * have both white shirt and cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 3,
                    Product::WHITE_SHIRT_TYPE => 3,
                ],
                5,
            ],
            /**
             * TC5
             * total < 7
             * have only white shirt
             */
            [
                [
                    Product::WHITE_SHIRT_TYPE => 3,
                ],
                0,
            ],
            /**
             * TC6
             * total < 7
             * have no cravat or white shirt
             */
            [
                [
                    Product::OTHER_TYPE => 6,
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
             * total >= 7
             * have cravat
             * have white shirt
             */
            [
                [
                    Product::CRAVAT_TYPE => 6,
                    Product::WHITE_SHIRT_TYPE => 1,
                ],
                12,
            ],
            /**
             * TC2
             * total >= 7
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
             * total >= 7
             * have only cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 10,
                ],
                7,
            ],
            /**
             * TC4
             * total >= 7
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
             * total < 7
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
             * total < 7
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
             * total < 7
             * have only cravat
             */
            [
                [
                    Product::CRAVAT_TYPE => 6,
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
