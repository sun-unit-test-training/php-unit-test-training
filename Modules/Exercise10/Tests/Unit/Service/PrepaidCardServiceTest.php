<?php

namespace Modules\Exercise10\Tests\Unit\Service;

use Tests\TestCase;
use Mockery as m;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Exercise10\Services\PrepaidCardService;
use Modules\Exercise10\Models\CardLevel;
use Modules\Exercise10\Contracts\Repositories\CardLevelRepository;

class PrepaidCardServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    protected $repositoryMock;

    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = m::mock(CardLevelRepository::class);
        $this->service = new PrepaidCardService($this->repositoryMock);
        $this->app->instance(CardLevelRepository::class, $this->repositoryMock);
    }

    protected function verifyUseCase($data, $model)
    {
        $expected = array_merge(
            $data,
            $this->generateResult($data['price'], $model->bonus, $data['ballot'])
        );

        $this->repositoryMock->shouldReceive('findBonus')
            ->andReturn($model);
        $rs = $this->service->getAmountBonus($data);

        $this->assertEquals($expected, $rs);
    }

    protected function generateResult($price, $bonus, $isBallot = false)
    {
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

    public function test_get_amount_bonus_sliver_3000()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 3200,
            'ballot' => false,
        ];
        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.sliver'),
            'amount_limit' => 3000,
            'bonus' => 1,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_sliver_5000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 5200,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.sliver'),
            'amount_limit' => 5000,
            'bonus' => 2,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_sliver_5000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 5200,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.sliver'),
            'amount_limit' => 5000,
            'bonus' => 2,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_sliver_10000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 12000,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.sliver'),
            'amount_limit' => 10000,
            'bonus' => 4,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_sliver_10000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.sliver'),
            'price' => 12000,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.sliver'),
            'amount_limit' => 10000,
            'bonus' => 4,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_gold_3000()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 3200,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.gold'),
            'amount_limit' => 3000,
            'bonus' => 3,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_gold_5000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 5200,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.gold'),
            'amount_limit' => 5000,
            'bonus' => 5,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_gold_5000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 5200,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.gold'),
            'amount_limit' => 5000,
            'bonus' => 5,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_gold_10000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 12000,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.gold'),
            'amount_limit' => 10000,
            'bonus' => 10,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_gold_10000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.gold'),
            'price' => 12000,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.gold'),
            'amount_limit' => 10000,
            'bonus' => 10,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_black_3000()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 3000,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.black'),
            'amount_limit' => 3000,
            'bonus' => 5,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_black_5000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 5200,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.black'),
            'amount_limit' => 5000,
            'bonus' => 7,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_black_5000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 5200,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.black'),
            'amount_limit' => 5000,
            'bonus' => 7,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_black_10000_y()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 12000,
            'ballot' => true,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.black'),
            'amount_limit' => 10000,
            'bonus' => 15,
        ]);

        $this->verifyUseCase($data, $model);
    }

    public function test_get_amount_bonus_black_10000_n()
    {
        // From use case
        $data = [
            'type' => config('exercise10.card_type.black'),
            'price' => 12000,
            'ballot' => false,
        ];

        // From data in database
        $model = (new CardLevel)->fill([
            'type' => config('exercise10.card_type.black'),
            'amount_limit' => 10000,
            'bonus' => 15,
        ]);

        $this->verifyUseCase($data, $model);
    }
}
