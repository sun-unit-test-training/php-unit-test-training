<?php

namespace Modules\Exercise04\Tests\Services;

use Illuminate\Support\Carbon;
use Modules\Exercise04\Services\CalendarService;
use Tests\SetupDatabaseTrait;
use Tests\TestCase;

class CalendarServiceTest extends TestCase
{
    use SetupDatabaseTrait;

    protected $calendarService;

    public function setUp():void
    {
        parent::setUp();
        $this->calendarService = new CalendarService;
    }

    public function test_normal_date_have_color_black()
    {
        $date = Carbon::createFromDate(2020, 9, 1);
        $this->assertEquals(CalendarService::COLOR_BLACK, $this->calendarService->getDateClass($date, []));
    }

    public function test_sun_day_have_color_red()
    {
        $date = Carbon::createFromDate(2020, 9, 27);
        $this->assertEquals(CalendarService::COLOR_RED, $this->calendarService->getDateClass($date, []));
    }

    public function test_saturday_have_color_blue()
    {
        $date = Carbon::createFromDate(2020, 9, 19);
        $this->assertEquals(CalendarService::COLOR_BLUE, $this->calendarService->getDateClass($date, []));
    }

    public function test_holiday_have_color_red()
    {
        $date = Carbon::createFromDate(2020, 9, 19);
        $this->assertEquals(CalendarService::COLOR_RED, $this->calendarService->getDateClass($date, ['2020-09-19']));
    }
}
