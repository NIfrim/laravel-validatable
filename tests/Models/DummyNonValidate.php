<?php

namespace Nifrim\LaravelValidatable\Tests\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nifrim\LaravelValidatable\Concerns\ValidatableAttributes;

/**
 * @property int $id
 * @property string $name
 */
class DummyNonValidate extends EloquentModel
{
    use ValidatableAttributes;

    public $timestamps = false;

    /**
     * Table name
     * @var string
     */
    protected $table = 'dummy_non_validate';

    /**
     * @var string[]
     */
    protected $fillable = ['id', 'name'];
}
