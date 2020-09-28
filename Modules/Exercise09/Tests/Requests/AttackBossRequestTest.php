<?php

namespace Modules\Exercise09\Tests\Requests;

use Modules\Exercise09\Http\Requests\AttackBossRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class AttackBossRequestTest extends TestCase
{
    /** @var AttackBossRequest */
    protected $attackBossRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attackBossRequest = new AttackBossRequest();
    }

    public function test_validation_fails_with_wrong_data()
    {
        // TODO: Add more fail case
        $data = [
            'dua_phep' => 2,
            'quan_su' => 'a',
            'chia_khoa' => 'a',
            'kiem_anh_sang' => 'a',
        ];
        $validator = Validator::make($data, $this->attackBossRequest->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_success()
    {
        // TODO: Add more pass case
        $data = [
            'dua_phep' => 1,
            'quan_su' => 0,
            'chia_khoa' => true,
            'kiem_anh_sang' => false,
        ];
        $validator = Validator::make($data, $this->attackBossRequest->rules());
        $this->assertTrue($validator->passes());

    }
}
