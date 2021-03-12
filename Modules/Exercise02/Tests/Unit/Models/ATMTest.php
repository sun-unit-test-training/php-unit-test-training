<?php

namespace Modules\Exercise02\Tests\Unit\Models;

use Modules\Exercise02\Models\ATM;
use Tests\SetupDatabaseTrait;
use Tests\TestCase;

class ATMTest extends TestCase
{
    use SetupDatabaseTrait;

    /**
     * This test does not count coverage for model ATM,
     * because we are test for class property `casts`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    public function test_field_is_vip_is_cast_to_boolean()
    {
        $inputs = [
            'card_id' => '123456',
            'is_vip' => 1,
        ];

        $atm = (new ATM())->fill($inputs);

        $this->assertSame(true, $atm->is_vip);
    }
}
