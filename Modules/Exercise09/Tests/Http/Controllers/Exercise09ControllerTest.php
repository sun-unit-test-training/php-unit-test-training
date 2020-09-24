<?php

namespace Modules\Exercise09\Tests\Http\Controllers;

use Mockery;
use Modules\Exercise09\Http\Controllers\Exercise09Controller;
use Modules\Exercise09\Services\CombatService;
use Tests\TestCase;

class Exercise09ControllerTest extends TestCase
{
    protected $combatService;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->combatService = Mockery::mock($this->app->make(CombatService::class));
        });

        parent::setUp();
    }

    public function test_it_render_index_page()
    {
        $controller = new Exercise09Controller($this->combatService);
        $view = $controller->index();

        $this->assertEquals('exercise09::index', $view->getName());
    }

    public function test_it_return_back_after_submit()
    {
        $this->withoutMiddleware();
        $response = $this->post('/exercise09', []);
        $response->assertStatus(302);
        $response->assertSessionHas('status');
        $response->assertRedirect(session()->previousUrl());
    }
}
