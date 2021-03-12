<?php

namespace Modules\Exercise03\Tests\Unit\Models;

use Modules\Exercise03\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * This test does not count coverage for model Product,
     * because we are test for class property `fillable`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    public function test_fields_are_fillable()
    {
        $inputs = [
            'name' => '123456',
            'type' => Product::CRAVAT_TYPE,
        ];
        $atm = (new Product())->fill($inputs);
        $this->assertEquals($inputs['name'], $atm->name);
        $this->assertEquals($inputs['type'], $atm->type);
    }
}
