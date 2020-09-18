<?php

namespace Modules\Exercise03\Services;

use InvalidArgumentException;
use Modules\Exercise03\Entities\Product;
use Modules\Exercise03\Repositories\EloquentProductRepository;

/**
 * Class ProductService
 * @package Modules\Exercise03\Services
 */
class ProductService
{
    const CRAVAT_WHITE_SHIRT_DISCOUNT = 5;
    const QUANTITY_DISCOUNT = 7;
    const TOTAL_PRODUCT_TO_DISCOUNT = 7;

    /**
     * @var EloquentProductRepository
     */
    protected $productRepository;

    /**
     * ProductService constructor.
     * @param EloquentProductRepository $productRepository
     */
    public function __construct(EloquentProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Calculate discount by products
     *
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

        if (($cravat + $whiteShirt + $others) >= self::TOTAL_PRODUCT_TO_DISCOUNT) {
            $discount += self::QUANTITY_DISCOUNT;
        }

        return $discount;
    }

    /**
     * Get all of products
     *
     * @return \Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function getAllProducts()
    {
        return $this->productRepository->all();
    }
}
