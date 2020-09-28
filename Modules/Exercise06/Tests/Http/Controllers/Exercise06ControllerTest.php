<?php

namespace Modules\Exercise06\Tests\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Exercise06\Http\Controllers\Exercise06Controller;
use Modules\Exercise06\Http\Requests\Exercise06Request;
use Modules\Exercise06\Services\CaculateService;
use Tests\TestCase;

class Exercise06ControllerTest extends TestCase
{
    /**
     * @var Exercise06Controller
     */
    protected $controller;

    /**
     * @var \Mockery\MockInterface
     */
    protected $caculateService;

    protected function setUp(): void
    {
        parent::setup();

        $this->caculateService = \Mockery::mock(CaculateService::class);
        $this->controller = new Exercise06Controller($this->caculateService);
    }

    public function test_index_return_view()
    {
        $this->assertTrue(true);
        $response = $this->controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('exercise06::index', $response->getName());
        $this->assertEquals([
            'case1' => $this->caculateService::CASE_1,
            'case2' => $this->caculateService::CASE_2,
            'freeTimeForMovie' => $this->caculateService::FREE_TIME_FOR_MOVIE,
        ], $response->getData());
    }

    public function test_function_caculate()
    {
        $request = \Mockery::mock(Exercise06Request::class);
        $request->shouldReceive('validated')->andReturn([
            'bill' => 200,
        ]);
        $time = 0;
        $this->caculateService->shouldReceive('calculate')->andReturn($time);
        $response = $this->controller->caculate($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(['time' => $time], $response->getSession()->all()['caculate']);
    }
}
