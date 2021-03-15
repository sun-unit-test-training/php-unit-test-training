<?php

namespace Modules\Exercise05\Tests\Unit\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Modules\Exercise05\Http\Requests\OrderRequest;
use Tests\TestCase;

class OrderRequestTest extends TestCase
{
    protected $orderRequest;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRequest = new OrderRequest();
    }

    /**
     * @test
     */
    public function validate_success()
    {
        $input = [
            'price' => 10,
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];

        $validator = Validator::make($input, $this->orderRequest->rules());
        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function validate_with_missing_price()
    {
        $input = [
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];

        $this->assertFailValidate($input, 'price');
    }

    /**
     * @test
     */
    public function validate_with_invalid_price()
    {
        $input = [
            'price' => "'10000'",
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => config('exercise05.no_coupon'),
        ];

        $this->assertFailValidate($input, 'price');
    }

    /**
     * @test
     */
    public function validate_with_missing_option_receive()
    {
        $input = [
            'price' => 1000,
            'option_coupon' => config('exercise05.no_coupon'),
        ];

        $this->assertFailValidate($input, 'option_receive');
    }

    /**
     * @test
     */
    public function validate_with_invalid_option_receive()
    {
        $input = [
            'price' => 1000,
            'option_receive' => 3,
            'option_coupon' => config('exercise05.no_coupon'),
        ];

        $this->assertFailValidate($input, 'option_receive');
    }

    /**
     * @test
     */
    public function validate_with_missing_option_coupon()
    {
        $input = [
            'price' => 1000,
            'option_receive' => config('exercise05.receive_at_store'),
        ];

        $this->assertFailValidate($input, 'option_coupon');
    }

    /**
     * @test
     */
    public function validate_with_invalid_option_coupon()
    {
        $input = [
            'price' => 1000,
            'option_receive' => config('exercise05.receive_at_store'),
            'option_coupon' => 4,
        ];

        $this->assertFailValidate($input, 'option_coupon');
    }

    private function assertFailValidate($input, $expectedKey)
    {
        $validator = Validator::make($input, $this->orderRequest->rules());
        $this->assertFalse($validator->passes());
        $errors = $validator->errors();
        $this->assertArrayHasKey($expectedKey, $errors->getMessageBag()->messages());
    }
}
