<?php

namespace Modules\Exercise07\Tests\Unit\Http\Requests;

use Modules\Exercise07\Http\Requests\CheckoutRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class CheckoutRequestTest extends TestCase
{
    protected $checkoutRequest;

    public function setUp(): void
    {
        parent::setUp();

        $this->checkoutRequest = new CheckoutRequest();
    }

    /**
     * @test
     */
    public function validate_success()
    {
        $input = [
            'amount' => 10,
        ];

        $validator = Validator::make($input, $this->checkoutRequest->rules());
        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function validate_with_amount_is_empty()
    {
        $input = [];
        $expected = [
            'amount' => [
                'The amount field is required.'
            ]
        ];

        $validator = Validator::make($input, $this->checkoutRequest->rules());
        $this->assertTrue($validator->fails());
        //or
        //$this->assertFalse($validator->passes());
        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->getMessages());
    }

    /**
     * @test
     */
    public function validate_with_amount_is_not_integer()
    {
        $input = [
            'amount' => 'test',
        ];
        $expected = [
            'amount' => [
                'The amount must be an integer.'
            ]
        ];

        $validator = Validator::make($input, $this->checkoutRequest->rules());
        $this->assertTrue($validator->fails());
        //or
        //$this->assertFalse($validator->passes());
        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->getMessages());
    }

    /**
     * @test
     */
    public function validate_with_amount_is_not_min_value()
    {
        $input = [
            'amount' => 0,
        ];
        $expected = [
            'amount' => [
                'The amount must be at least 1.'
            ]
        ];

        $validator = Validator::make($input, $this->checkoutRequest->rules());
        $this->assertTrue($validator->fails());
        //or
        //$this->assertFalse($validator->passes());
        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->getMessages());
    }
}
