<?php

namespace Modules\Exercise04\Tests\Feature\Http\Controllers;

use Carbon\Carbon;
use Mockery;
use Modules\Exercise04\Http\Controllers\CalendarController;
use Modules\Exercise04\Services\CalendarService;
use Tests\TestCase;
use Tests\SetupDatabaseTrait;

class CalendarControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    /** @var \Mockery\MockInterface|CalendarService */
    protected $calendarServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calendarServiceMock = $this->mock(CalendarService::class);
    }

    function test_it_show_calendar_view()
    {
        $url = action([CalendarController::class, 'index']);

        $dummyClass = CalendarService::COLOR_BLACK;
        $this->calendarServiceMock->shouldReceive('getDateClass')->andReturn($dummyClass);

        $response = $this->get($url);

        $response->assertViewIs('exercise04::calendar');
        $response->assertViewHas('calendars');
    }

    function test_it_show_correct_calendar()
    {
        $url = action([CalendarController::class, 'index']);

        $fakeColorClass = 'my-color';
        $this->calendarServiceMock->shouldReceive('getDateClass')
            ->withArgs([Mockery::type(Carbon::class), ['2020-09-26']])
            ->andReturnUsing(function ($date) use ($fakeColorClass) {
                if ($date->format('d') == 1) {
                    return $fakeColorClass;
                }
                return null;
            });

        $response = $this->get($url);
        $calendars = $response->viewData('calendars');

        $response->assertViewIs('exercise04::calendar');
        $response->assertViewHas('calendars');
        $this->assertInstanceOf(Carbon::class, $calendars[0][0]['date']);
        $this->assertEquals('2020-09-01', $calendars[0][0]['date']->format('Y-m-d'));
        $this->assertEquals($fakeColorClass, $calendars[0][0]['class']);
        $this->assertCount(7, $calendars[0]);
    }
}
