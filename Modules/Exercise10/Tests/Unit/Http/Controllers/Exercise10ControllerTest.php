<?php

namespace Modules\Exercise10\Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Http\Request;
use Modules\Exercise10\Http\Requests\PrepaidRequest;
use Modules\Exercise10\Contracts\Services\PrepaidInterface;
use Modules\Exercise10\Http\Controllers\Exercise10Controller;
use Mockery as m;

class Exercise10ControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index_view()
    {
        $service = m::mock(PrepaidInterface::class);
        $controler = new Exercise10Controller($service);
        $request = new Request();
        $eq = $controler->index($request);

        $this->assertEquals('exercise10::index', $eq->getName());
    }

    public function test_prepaid_view()
    {
        $resutls = [
            'type' => 1,
            'price' => 10000,
            'ballot' => 1,
        ];
        $mService = m::mock(PrepaidInterface::class);
        $mService->shouldReceive('getAmountBonus')->andReturn($resutls);
        $this->app->instance(PrepaidInterface::class, $mService);

        $controler = new Exercise10Controller($mService);
        $request = new PrepaidRequest($resutls);

        $eq = $controler->prepaid($request);
        $this->assertEquals('exercise10::index', $eq->getName());
        $this->assertEquals([
            'results' => $resutls,
        ], $eq->getData());
    }
}
