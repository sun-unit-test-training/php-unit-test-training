<?php

namespace Modules\Exercise10\Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Modules\Exercise10\Http\Requests\PrepaidRequest;

class PrepaidRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_rules()
    {
        $expected = [
            'type' => 'required|in:' . implode(',', config('exercise10.card_type')),
            'price' => 'required|integer',
            'ballot' => 'boolean'
        ];
        $result = (new PrepaidRequest)->rules();
        $this->assertEquals($expected, $result);
    }
}
