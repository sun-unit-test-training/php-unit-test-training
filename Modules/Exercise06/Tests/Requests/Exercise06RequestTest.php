<?php

namespace Modules\Exercise06\Tests\Http\Requests;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Modules\Exercise06\Http\Requests\Exercise06Request;

class Exercise06RequestTest extends TestCase
{
    public function test_validation_fails_when_request_empty()
    {
        $request = new Exercise06Request();
        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_with_bill_invalid()
    {
        $request = new Exercise06Request();
        $validator = Validator::make([
            'bill' => -1,
        ], $request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_successes()
    {
        $request = new Exercise06Request();
        $validator = Validator::make([
            'bill' => 100,
            'has_watch' => false,
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }
}
