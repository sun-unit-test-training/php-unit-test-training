<?php

namespace Modules\Exercise10\Tests\Unit\Repositories;

use Tests\TestCase;
use Modules\Exercise10\Models\CardLevel;
use Modules\Exercise10\Repositories\CardLevelEloquent;
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

        // Mock query builder twice where
        $mModel->shouldReceive('where')->twice()->andReturn($mModel);
        // Mock query builder orderByDesc
        $mModel->shouldReceive('orderByDesc')->times(1)->andReturn($mModel);;
        // Mock Results of query in model
        $mModel->shouldReceive('first')->andReturn($expected);

        $repo = new CardLevelEloquent($mModel);

        $rs = $repo->findBonus(1, 1000);
        $this->assertEquals($expected, $rs);
    }
}
