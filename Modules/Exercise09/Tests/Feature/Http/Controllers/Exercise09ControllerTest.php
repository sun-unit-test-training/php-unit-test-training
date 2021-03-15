<?php

namespace Modules\Exercise09\Tests\Feature\Http\Controllers;

use Modules\Exercise09\Http\Controllers\Exercise09Controller;
use Modules\Exercise09\Services\CombatService;
use Tests\TestCase;

class Exercise09ControllerTest extends TestCase
{
    protected $combatServiceMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->combatServiceMock = $this->mock(CombatService::class);
    }

    public function test_it_render_index_page()
    {
        $url = action([Exercise09Controller::class, 'index']);

        $response = $this->get($url);

        $response->assertViewIs('exercise09::index');
    }

    public function test_it_return_back_after_submit_with_valid_input()
    {
        $input = [
            'dua_phep' => 1,
            'quan_su' => 0,
            'chia_khoa' => true,
            'kiem_anh_sang' => false,
            'not_related_input_should_be_ignored' => 'abc123',
        ];

        $stubResult = 100;
        $this->combatServiceMock->shouldReceive('calculateAttackResult')
            ->once()
            ->with([
                'dua_phep' => 1,
                'quan_su' => 0,
                'chia_khoa' => true,
                'kiem_anh_sang' => false,
            ])
            ->andReturn($stubResult);

        $url = action([Exercise09Controller::class, 'attack']);

        $response = $this->post($url, $input);

        $response->assertStatus(302);
        $response->assertSessionHas('status', $stubResult);
        $response->assertSessionHasInput([
            'dua_phep',
            'quan_su',
            'chia_khoa',
            'kiem_anh_sang',
        ]);
    }

    public function test_it_return_back_after_submit_with_invalid_input()
    {
        $input = [
            'dua_phep' => 2,
            'quan_su' => 'not-a-boolean',
            'chia_khoa' => 'not-a-boolean',
            'kiem_anh_sang' => 'not-a-boolean',
        ];

        $this->combatServiceMock->shouldNotReceive('calculate');

        $url = action([Exercise09Controller::class, 'attack']);

        $response = $this->post($url, $input);

        $response->assertSessionHasErrors([
            'dua_phep',
            'quan_su',
            'chia_khoa',
            'kiem_anh_sang',
        ]);
        $response->assertSessionMissing('status');
        $response->assertSessionHasInput(array_keys($input));
    }
}
