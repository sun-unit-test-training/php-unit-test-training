<?php

namespace Modules\Exercise02\Tests\Feature\Models;

use Modules\Exercise02\Models\ATM;
use Tests\SetupDatabaseTrait;
use Tests\TestCase;

class ATMTest extends TestCase
{
    use SetupDatabaseTrait;

    /**
     * This test does not count coverage for model ATM,
     * because we are test for class property `fillable`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    public function test_fields_are_fillable()
    {
        $inputs = [
            'card_id' => '123456',
            'is_vip' => 1,
        ];

        $atm = ATM::create($inputs);

        $this->assertEquals($inputs['card_id'], $atm->card_id);
        $this->assertEquals($inputs['is_vip'], $atm->is_vip);
    }
}
