<?php

namespace Modules\Exercise06\Tests\Feature\Http\Controllers;

use Modules\Exercise06\Http\Controllers\Exercise06Controller;
use Modules\Exercise06\Services\CalculateService;
use Tests\TestCase;

class Exercise06ControllerTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $calculateServiceMock;

    protected function setUp(): void
    {
        parent::setup();

        // Laravel helper: mock and bind to service container
        $this->calculateServiceMock = $this->mock(CalculateService::class);
    }

    function test_index_return_view()
    {
        $url = action([Exercise06Controller::class, 'index']);
        $response = $this->get($url);

        $response->assertViewIs('exercise06::index');
        $response->assertViewHasAll([
            'case1',
            'case2',
            'freeTimeForMovie',
        ]);
        $response->assertSessionMissing('result');
    }

    function test_it_show_error_when_missing_input_bill()
    {
        $url = action([Exercise06Controller::class, 'calculate']);

        $response = $this->post($url);

        $response->assertSessionHasErrors(['bill']);
    }

    function test_it_error_when_input_wrong_bill()
    {
        $url = action([Exercise06Controller::class, 'calculate']);

        $response = $this->post($url, [
            'bill' => 'test',
        ]);

        $response->assertSessionHasErrors(['bill']);
    }

    function test_it_error_when_input_wrong_has_watch()
    {
        $url = action([Exercise06Controller::class, 'calculate']);

        $response = $this->post($url, [
            'bill' => 100,
            'has_watch' => 'test'
        ]);

        $response->assertSessionHasErrors(['has_watch']);
    }

    function test_it_return_result_when_input_valid_bill_and_has_watch()
    {
        $time = 180;
        $this->calculateServiceMock
            ->shouldReceive('calculate')
            ->andReturn($time);
        $url = action([Exercise06Controller::class, 'calculate']);
        $response = $this->post($url, [
            'bill' => 200,
            'has_watch' => true,
        ]);

        $response->assertSessionDoesntHaveErrors(['bill']);
        $response->assertSessionDoesntHaveErrors(['has_watch']);
        $response->assertSessionHasInput(['bill', 'has_watch']);
        $response->assertSessionHas('result', function ($result) use ($time) {
            return $result['time'] == $time;
        });
    }
}
