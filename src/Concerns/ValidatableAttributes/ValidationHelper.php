<?php

namespace Nifrim\LaravelValidatable\Concerns\ValidatableAttributes;

use \Illuminate\Support\Str;
use Nifrim\LaravelValidatable\Contracts\ValidationHelper as ContractsValidationHelper;

/**
 * @phpstan-import-type TValidationRules from \Nifrim\LaravelValidatable\Contracts\ValidationHelper
 * @phpstan-import-type TValidationMessages from \Nifrim\LaravelValidatable\Contracts\ValidationHelper
 * 
 * @property TValidationRules $validationRules
 * @property TValidationMessages $validationMessages
 */
class ValidationHelper implements ContractsValidationHelper
{
    /**
     * The model class using this validator.
     * 
     * @var string
     */
    protected string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function __get($property)
    {
        $validatorClass = $this->getValidatorClass();
        if ($validatorClass && method_exists($validatorClass, $property)) {
            return call_user_func([$validatorClass, $property]);
        }

        if (isset($this->{$property})) {
            return $this->{$property};
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getValidatorClass(): ?string
    {
        $namespace = config('validatable.namespace', 'App\\Validators');
        $validatorClass = "{$namespace}\\" . Str::after($this->modelClass, 'Models\\');
        if (class_exists($validatorClass)) {
            return $validatorClass;
        }
        return null;
    }
}
