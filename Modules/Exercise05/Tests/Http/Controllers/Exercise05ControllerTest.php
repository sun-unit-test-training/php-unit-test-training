<?php

namespace Modules\Exercise05\Tests\Http\Controllers;

use Tests\TestCase;
use Modules\Exercise05\Http\Controllers\Exercise05Controller;

/**
 * TODO: make real test
 */
class Exercise05ControllerTest extends TestCase
{
    public function test_can_create_order_success()
    {
        $url = action([Exercise05Controller::class, 'index']);
        $attribute = [
            'price' => 1499,
            'option_receive' => 1,
            'option_coupon' => 1
        ];
        $this->get($url)
            ->assertStatus(200)
            ->assertViewIs('exercise05::index');

        $this->post($url, $attribute)
            ->assertStatus(200)
            ->assertViewIs('exercise05::detail');
    }

    public function test_can_create_order_fail_by_validate()
    {
        $url = action([Exercise05Controller::class, 'store']);
        $attribute = [
            'price' => 'aaa',
            'option_receive' => 1,
            'option_coupon' => 1
        ];
        $this->get($url)
             ->assertStatus(200)
             ->assertViewIs('exercise05::index');

        $this->postJson($url, $attribute)
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['price']]);    
    }

}
