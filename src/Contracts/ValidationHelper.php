<?php

namespace Nifrim\LaravelValidatable\Contracts;

/**
 * @phpstan-type TValidationRules  array{create: array<string,string>, update: array<string,string>}
 * @phpstan-type TValidationMessages  array{create: array<string,string>, update: array<string,string>}
 * 
 * @property TValidationRules $validationRules
 * @property TValidationMessages $validationMessages
 */
interface ValidationHelper
{
    /**
     * Method to get the validator content from the related validator class.
     * 
     * @return ?string
     */
    public function getValidatorClass(): ?string;
}
