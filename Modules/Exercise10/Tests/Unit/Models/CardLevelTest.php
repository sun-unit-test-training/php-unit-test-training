<?php

namespace Modules\Exercise10\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Exercise10\Models\CardLevel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CardLevelTest extends TestCase
{
    /**
     * @param Model $model
     * @param array $assertions
     *
     * - `fillable` -> `getFillable()`
     * - `guarded` -> `getGuarded()`
     * - `table` -> `getTable()`
     * - `primaryKey` -> `getKeyName()`
     * - `connection` -> `getConnectionName()`: in case multiple connections exist.
     * - `hidden` -> `getHidden()`
     * - `visible` -> `getVisible()`
     * - `casts` -> `getCasts()`: note that method appends incrementing key.
     * - `dates` -> `getDates()`: note that method appends `[static::CREATED_AT, static::UPDATED_AT]`.
     * - `newCollection()`: assert collection is exact type. Use `assertEquals` on `get_class()` result, but not `assertInstanceOf`.
     */
    protected function runConfigurationAssertions(Model $model, $assertions)
    {
        $assertions = array_merge([
            'fillable' => [],
            'hidden' => [],
            'guarded' => ['*'],
            'visible' => [],
            'casts' => ['id' => 'int'],
            'dates' => ['created_at', 'updated_at'],
            'collectionClass' => Collection::class,
            'table' => null,
            'primaryKey' => 'id',
            'connection' => null,
        ], $assertions);
        extract($assertions);
        $this->assertEquals($assertions['fillable'], $model->getFillable());
        $this->assertEquals($assertions['guarded'], $model->getGuarded());
        $this->assertEquals($assertions['hidden'], $model->getHidden());
        $this->assertEquals($assertions['visible'], $model->getVisible());
        $this->assertEquals($assertions['casts'], $model->getCasts());
        $this->assertEquals($assertions['dates'], $model->getDates());
        $this->assertEquals($assertions['primaryKey'], $model->getKeyName());
        $c = $model->newCollection();
        $this->assertEquals($assertions['collectionClass'], get_class($c));
        $this->assertInstanceOf(Collection::class, $c);
        if ($assertions['connection'] !== null) {
            $this->assertEquals($assertions['connection'], $model->getConnectionName());
        }
        if ($assertions['table'] !== null) {
            $this->assertEquals($assertions['table'], $model->getTable());
        }
    }

    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new CardLevel(),
            [
                'fillable' => [
                    'type',
                    'amount_limit',
                    'bonus',
                ],
                [],
                'dates' => [
                    'created_at',
                    'updated_at'
                ]
            ]
        );
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_fields_are_fillable()
    {
        $inputs = [
            'type' => 1,
            'amount_limit' => 10000,
            'bonus' => 10,
        ];

        $cardLevel = (new CardLevel())->fill($inputs);

        $this->assertEquals($inputs['type'], $cardLevel->type);
        $this->assertEquals($inputs['amount_limit'], $cardLevel->amount_limit);
        $this->assertEquals($inputs['bonus'], $cardLevel->bonus);
    }
}
