<?php

namespace Modules\Exercise10\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Modules\Exercise10\Http\Controllers\Exercise10Controller;
use Tests\SetupDatabaseTrait;
use Illuminate\Http\Response;
use Modules\Exercise10\Database\Seeders\Exercise10DatabaseSeeder;

class Exercise10ControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    public function setUp():void
    {
        parent::setUp();
        $this->seed(Exercise10DatabaseSeeder::class);
    }

    protected function generateResult($data, $bonus)
    {
        $price = $data['price'] ?? 0;
        $isBallot = $data['ballot'] ?? false;
        $result = [
            'bonus' => 0,
            'amount' => $price,
        ];
        if ($isBallot) {
            $result = [
                'bonus' => $price * $bonus / 100,
                'amount' => $price * (100 - $bonus) / 100,
            ];
        }

        return $result;
    }

    protected function assertDataFromRequest(array $data, array $expected = [])
    {
        $url = action([Exercise10Controller::class, 'prepaid']);
        $response = $this->post($url, $data);

        $response->assertViewIs('exercise10::index');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('results', $expected);
    }

    public function test_view_index()
    {
        $url = action([Exercise10Controller::class, 'index']);

        $response = $this->get($url);

        $response->assertViewIs('exercise10::index');
    }

    public function test_prepaid_get_amount_bonus_sliver_3000()
    {
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 3200,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 1)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_sliver_5000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 5200,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 2)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_sliver_5000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 5200,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 2)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_sliver_10000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 12000,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 4)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_sliver_10000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 12000,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 4)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_gold_3000()
    {
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 3200,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 2)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_gold_5000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 5200,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 5)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_gold_5000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 5200,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 5)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_gold_10000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 12000,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 10)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_gold_10000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 12000,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 10)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_black_3000()
    {
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 3000,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 5)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_black_5000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 5200,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 7)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_black_5000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 5200,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 7)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_black_10000_y()
    {
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 12000,
            'ballot' => true,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 15)
        );

        $this->assertDataFromRequest($data, $expected);
    }

    public function test_prepaid_get_amount_bonus_black_10000_n()
    {
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 12000,
            'ballot' => false,
        ];

        $expected = array_merge(
            $data,
            $this->generateResult($data, 15)
        );

        $this->assertDataFromRequest($data, $expected);
    }
}
