<?php

namespace Modules\Exercise01\Tests\Unit\Models;

use Modules\Exercise01\Models\Voucher;
use Tests\TestCase;

/**
 * When we use trait DatabaseTransactions or RefreshDatabase,
 * Laravel will setup transaction or refresh entire db on each test
 * => That slows down our test
 *
 * To speed up test run time, we divide unit and feature test
 * to reduce connection to database.
 * Test cases which do not need to use db => unit test
 */
class VoucherTest extends TestCase
{
    /**
     * This test does not count coverage for model Voucher,
     * because we are test for class property `casts`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    function test_field_is_active_is_cast_to_boolean()
    {
        $inputs = [
            'code' => 'voucher-code',
            'is_active' => 1,
        ];

        // No need to persist to db to test cast
        $voucher = (new Voucher())->fill($inputs);

        $this->assertSame(true, $voucher->is_active);
    }
}
