<?php

namespace Modules\Exercise10\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Exercise10\Models\CardLevel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CardLevelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_fields_are_fillable()
    {
        $inputs = [
            'type' => 1,
            'amount_limit' => 10000,
            'bonus' => 10,
        ];

        $cardLevel = (new CardLevel())->fill($inputs);

        $this->assertEquals($inputs['type'], $cardLevel->type);
        $this->assertEquals($inputs['amount_limit'], $cardLevel->amount_limit);
        $this->assertEquals($inputs['bonus'], $cardLevel->bonus);
    }
}
