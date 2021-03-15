<?php

namespace Modules\Exercise06\Tests\Unit\Services;

use Tests\TestCase;
use InvalidArgumentException;
use Modules\Exercise06\Services\CalculateService;

class CalculateServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CalculateService();
    }

    function test_it_throws_exception_when_bill_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->calculate(-1, false);
    }

    function test_it_calculate_when_bill_less_than_2000()
    {
        list($minBill) = $this->service::CASE_1;
        $time = $this->service->calculate($minBill - rand(0, $minBill), false);

        $this->assertEquals(0, $time);
    }

    function test_it_calculate_when_bill_more_than_2000_and_less_than_5000()
    {
        list($minBill, $freeTime) = $this->service::CASE_1;
        $time = $this->service->calculate($minBill + rand(0, null), false);

        $this->assertEquals($freeTime, $time);
    }

    function test_it_calculate_when_bill_more_than_5000()
    {
        list($minBill, $freeTime) = $this->service::CASE_2;
        $time = $this->service->calculate($minBill + rand(0, null), false);

        $this->assertEquals($freeTime, $time);
    }

    function test_it_calculate_when_bill_less_than_2000_and_user_watch_movie()
    {
        list($minBill) = $this->service::CASE_1;
        $time = $this->service->calculate($minBill - rand(0, $minBill), true);

        $this->assertEquals($this->service::FREE_TIME_FOR_MOVIE, $time);
    }

    function test_it_calculate_when_bill_more_than_2000_and_less_than_5000_and_user_watch_movie()
    {
        list($minBill, $freeTime) = $this->service::CASE_1;
        $time = $this->service->calculate($minBill + rand(0, null), true);

        $this->assertEquals($freeTime + $this->service::FREE_TIME_FOR_MOVIE, $time);
    }

    function test_it_calculate_when_bill_more_than_5000_and_user_watch_movie()
    {
        list($minBill, $freeTime) = $this->service::CASE_2;
        $time = $this->service->calculate($minBill + rand(0, null), true);

        $this->assertEquals($freeTime + $this->service::FREE_TIME_FOR_MOVIE, $time);
    }
}
