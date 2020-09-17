<?php

namespace Modules\Exercise03\Services;

use InvalidArgumentException;
use Modules\Exercise03\Entities\Product;

/**
 * Class ProductService
 * @package Modules\Exercise03\Services
 */
class ProductService
{
    const CRAVAT_WHITE_SHIRT_DISCOUNT = 5;
    const MORE_THAN_7_DISCOUNT = 7;

    /**
     * @param $totalProducts
     * @return mixed
     */
    public function calculateDiscount($totalProducts)
    {
        $cravat = $totalProducts[Product::CRAVAT_TYPE] ?? 0;
        $whiteShirt = $totalProducts[Product::WHITE_SHIRT_TYPE] ?? 0;
        $others = $totalProducts[Product::OTHER_TYPE] ?? 0;
        $discount = 0;

        if ($cravat < 0 || $whiteShirt < 0 || $others < 0) {
            throw new InvalidArgumentException();
        }

        if ($cravat > 0 && $whiteShirt > 0) {
            $discount = self::CRAVAT_WHITE_SHIRT_DISCOUNT;
        }

        if (($cravat + $whiteShirt + $others) > 7) {
            $discount += self::MORE_THAN_7_DISCOUNT;
        }

        return $discount;
    }
}
