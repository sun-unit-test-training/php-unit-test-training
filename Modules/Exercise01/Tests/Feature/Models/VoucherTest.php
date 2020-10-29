<?php

namespace Modules\Exercise01\Tests\Feature\Models;

use Modules\Exercise01\Models\Voucher;
use Tests\SetupDatabaseTrait;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use SetupDatabaseTrait;

    /**
     * This test does not count coverage for model Voucher,
     * because we are test for class property `fillable`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    public function test_fields_are_fillable()
    {
        $inputs = [
            'code' => 'voucher-code',
            'is_active' => 1,
        ];

        $voucher = Voucher::create($inputs);

        $this->assertEquals($inputs['code'], $voucher->code);
        $this->assertEquals($inputs['is_active'], $voucher->is_active);
    }

    /**
     * This test does not count coverage for model Voucher,
     * because we are test for class property `casts`, not method?
     *
     * But it is added to ensure we initialize property correctly
     */
    public function test_field_is_active_is_cast_to_boolean()
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
