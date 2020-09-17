<?php

namespace Modules\Exercise03\Tests\Http\Requests;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Modules\Exercise03\Http\Requests\CheckoutRequest;

class CheckoutRequestTest extends TestCase
{
    /**
     * @var CheckoutRequest
     */
    protected $checkoutRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkoutRequest = new CheckoutRequest();
    }

    public function test_validation_fails_with_empty_data()
    {
        $data = [];
        $validator = Validator::make($data, $this->checkoutRequest->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_with_wrong_number_products()
    {
        $data = [
            'total_products' => [
                1 => -1,
                2 => 5,
            ],
        ];
        $validator = Validator::make($data, $this->checkoutRequest->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_successes()
    {
        $data = [
            'total_products' => [
                1 => 1,
                2 => 5,
            ],
        ];
        $validator = Validator::make($data, $this->checkoutRequest->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_function_authorize()
    {
        $this->assertTrue($this->checkoutRequest->authorize());
    }
}
