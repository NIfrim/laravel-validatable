<?php

namespace Nifrim\LaravelValidatable\Contracts;

interface Validator
{
    /**
     * Return the validation messages.
     * 
     * @return array{create:array<array-key,mixed>,update:array<array-key,mixed>}
     */
    public static function messages(): array;

    /**
     * Return the validation rules.
     * 
     * @return array{create:array<array-key,mixed>,update:array<array-key,mixed>}
     */
    public static function rules(): array;

    /**
     * Return the form structure.
     * 
     * @return array<array-key,mixed>
     */
    public static function form(): array;
}
