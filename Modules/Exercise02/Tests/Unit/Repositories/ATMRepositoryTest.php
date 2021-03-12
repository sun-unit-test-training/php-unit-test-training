<?php

namespace Modules\Exercise02\Tests\Unit\Repositories;

use Tests\TestCase;
use Modules\Exercise02\Models\ATM;
use Modules\Exercise02\Repositories\ATMRepository;
use Tests\SetupDatabaseTrait;

class ATMRepositoryTest extends TestCase
{
    use SetupDatabaseTrait;

    /**
     * @var ATMRepository
     */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ATMRepository(new ATM());
    }

    public function test_it_can_find()
    {
        $card = ATM::factory()->create()->fresh();

        $this->assertEquals($card, $this->repository->find($card->card_id));
    }

    public function test_it_cannot_find_cart_does_not_exist()
    {
        $this->assertNull($this->repository->find(-1));
    }
}
