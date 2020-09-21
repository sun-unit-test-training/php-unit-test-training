<?php

namespace Modules\Exercise10\Tests\Unit\Repository;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Exercise10\Models\CardLevel;
use Modules\Exercise10\Contracts\Repositories\CardLevelRepository;
use Modules\Exercise10\Repositories\CardLevelEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;

class CardLevelEloquentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_find_bonus_function()
    {
        $expected = new CardLevel();
        $mModel = m::mock(CardLevel::class);
        $mModel->shouldReceive('where->where->orderByDesc->first')
            ->once()
            ->andReturn($expected);

        $repo = new CardLevelEloquent($mModel);

        $rs = $repo->findBonus(1, 1000);
        $this->assertEquals($expected, $rs);
    }
}
