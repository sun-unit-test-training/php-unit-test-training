<?php

namespace Modules\Exercise02\Tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Modules\Exercise02\Http\Requests\ATMRequest;
use Modules\Exercise02\Models\ATM;
use Tests\SetupDatabaseTrait;

class ATMRequestTest extends TestCase
{
    use SetupDatabaseTrait;

    public function test_it_contain_default_rules()
    {
        $request = new ATMRequest();

        $this->assertEquals([
            'card_id' => 'required|exists:atms,card_id',
        ], $request->rules());
    }

    public function test_validation_fails_when_data_empty()
    {
        $request = new ATMRequest();
        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_card_does_not_exist()
    {
        $request = new ATMRequest();
        $validator = Validator::make([
            'card_id' => -1,
        ], $request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_success()
    {
        $request = new ATMRequest();
        $card = ATM::factory()->create()->fresh();
        $validator = Validator::make([
            'card_id' => $card->card_id,
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }
}
