<?php

namespace Modules\Exercise03\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Exercise02\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    const CRAVAT_TYPE = 1;
    const WHITE_SHIRT_TYPE = 2;
    const OTHER_TYPE = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exercise03_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type'];

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
