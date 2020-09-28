<?php

namespace Modules\Exercise04\Tests\Http\Controllers;

use Modules\Exercise04\Http\Controllers\CalendarController;
use Tests\TestCase;
use Tests\SetupDatabaseTrait;

class CalendarControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    function test_it_show_calendar_view()
    {
        $url = action([CalendarController::class, 'index']);

        $response = $this->get($url);

        $response->assertViewIs('exercise04::calendar');
        $response->assertViewHasAll([
            'calendars',
        ]);
    }
}
