<?php

namespace Modules\Exercise02\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Exercise02\Http\Controllers\Exercise02Controller;
use Modules\Exercise02\Http\Requests\ATMRequest;
use Modules\Exercise02\Models\ATM;
use Modules\Exercise02\Repositories\ATMRepository;
use Modules\Exercise02\Services\ATMService;
use Tests\SetupDatabaseTrait;

class Exercise02ControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    /**
     * @var Exercise02Controller
     */
    protected $controller;

    /**
     * @var ATMService
     */
    protected $atmService;

    protected function setUp(): void
    {
        parent::setup();
        $this->atmService = new ATMService(new ATMRepository(new ATM()));
        $this->controller = new Exercise02Controller($this->atmService);
    }

    public function test_index_return_view()
    {
        $response = $this->controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('exercise02::index', $response->getName());
        $this->assertEquals([
            'normalFee' => $this->atmService::NORMAL_FEE,
            'noFee' => $this->atmService::NO_FEE,
            'timePeriod1' => $this->atmService::TIME_PERIOD_1,
            'timePeriod2' => $this->atmService::TIME_PERIOD_2,
            'timePeriod3' => $this->atmService::TIME_PERIOD_3,
        ], $response->getData());
    }

    public function test_function_calculate()
    {
        $request = \Mockery::mock(ATMRequest::class);
        $card = ATM::factory()->isVip()->create()->fresh();
        $request->shouldReceive('validated')->andReturn([
            'card_id' => $card->card_id,
        ]);
        $correctAnswer = ['fee' => 0];
        $response = $this->controller->takeATMFee($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($correctAnswer, $response->getSession()->all()['calculate']);
    }
}
