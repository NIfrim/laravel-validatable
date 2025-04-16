<?php

namespace Nifrim\LaravelValidatable\Tests\Models\DummyValidate;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nifrim\LaravelValidatable\Concerns\ValidatableAttributes;

class Other extends EloquentModel
{
    use ValidatableAttributes;

    public $timestamps = false;

    /**
     * Table name
     * @var string
     */
    protected $table = 'dummy_validate_other';

    /**
     * @var string[]
     */
    protected $fillable = ['id', 'name'];
}
