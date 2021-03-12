<?php

namespace Modules\Exercise04\Tests\Http\Controllers;

use Modules\Exercise04\Http\Controllers\CalendarController;
use Modules\Exercise04\Services\CalendarService;
use Tests\TestCase;
use Tests\SetupDatabaseTrait;

class CalendarControllerTest extends TestCase
{
    use SetupDatabaseTrait;

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
        $response->assertViewHasAll([
            'calendars',
        ]);
    }
}
