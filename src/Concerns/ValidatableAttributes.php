<?php

namespace Nifrim\LaravelValidatable\Concerns;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Nifrim\LaravelValidatable\Concerns\ValidatableAttributes\ValidationHelper;

trait ValidatableAttributes
{
    protected ValidationHelper $validationHelper;

    /**
     * Override the model constructor to validate attributes when instantiating.
     *
     * @param  array   $attributes
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __construct(array $attributes = [], ?ValidationHelper $validationHelper = null)
    {
        parent::__construct($attributes);
        $this->validationHelper = $validationHelper ?: new ValidationHelper($this::class);
        if (!empty($this->attributesToArray()) && $this->shouldValidate()) {
            $this->validateAttributes($this->attributesToArray());
        }
    }

    /**
     * Boot the validatable attributes trait for a model.
     *
     * @return void
     */
    public static function bootValidatableAttributes()
    {
        static::creating(function ($model) {
            // This will fire the validatingCreate event and perform validation.
            if ($model->shouldValidate()) {
                $model->validateAttributes();
            }
        });

        static::updating(function ($model) {
            // This will fire the validatingUpdate event and perform validation.
            if ($model->shouldValidate()) {
                $model->validateAttributes();
            }
        });
    }

    /**
     * Get the combined validation rules for a given scenario.
     *
     * @param string $scenario - The scenario for the validation rules, either create/update.
     * 
     * @return array
     */
    protected function getValidationRules(string $scenario)
    {
        if ($rules = $this->validationHelper->rules) {
            return $rules[$scenario];
        }
        return [];
    }

    /**
     * Get the combined validation messages for a given scenario.
     *
     * @param string $scenario - The scenario for the validation messages, either create/update.
     * 
     * @return array
     */
    protected function getValidationMessages(string $scenario)
    {
        if ($messages = $this->validationHelper->messages) {
            return $messages[$scenario];
        }
        return [];
    }

    /**
     * Validate model attributes against the rules for a scenario.
     *
     * Fires events:
     *   - "validating" before validation.
     *   - "validated" after validation passes.
     *
     * @param  ?array   $data
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateAttributes(?array $data = null)
    {
        // Fire the "validating{Scenario}" event. If a listener returns false, abort validation.
        $scenario = $this->exists ? 'update' : 'create';
        $data = $data ?: $this->attributesToArray();
        if ($this->fireModelEvent("validating" . Str::ucfirst($scenario)) === false) {
            return false;
        }

        $rules = $this->getValidationRules($scenario);
        $messages = $this->getValidationMessages($scenario);
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Fire the "validated{Scenario}" event after successful validation.
        $this->fireModelEvent("validated{$scenario}", false);

        return true;
    }

    /**
     * 
     * @return bool
     */
    public function shouldValidate()
    {
        return true;
    }

    /**
     * Register a "validatingCreate" model event callback with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|callable|string  $callback
     * @return void
     */
    public static function validatingCreate($callback)
    {
        static::registerModelEvent('validatingCreate', $callback);
    }

    /**
     * Register a "validatedCreate" model event callback with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|callable|string  $callback
     * @return void
     */
    public static function validatedCreate($callback)
    {
        static::registerModelEvent('validatedCreate', $callback);
    }

    /**
     * Register a "validatingUpdate" model event callback with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|callable|string  $callback
     * @return void
     */
    public static function validatingUpdate($callback)
    {
        static::registerModelEvent('validatingUpdate', $callback);
    }

    /**
     * Register a "validatedUpdate" model event callback with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|callable|string  $callback
     * @return void
     */
    public static function validatedUpdate($callback)
    {
        static::registerModelEvent('validatedUpdate', $callback);
    }
}
