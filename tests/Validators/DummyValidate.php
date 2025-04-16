<?php

namespace Nifrim\LaravelValidatable\Tests\Validators;

use Nifrim\LaravelValidatable\Contracts\Validator;

class DummyValidate implements Validator
{
    /**
     * Return the validation messages.
     */
    public static function messages(): array
    {
        return [
            "create" => [],
            "update" => []
        ];
    }

    /**
     * Return the validation rules.
     */
    public static function rules(): array
    {
        return [
            "create" => [
                "name" => "bail|required|min:3",
                "email" => "bail|required|email|unique:dummy_validate,email"
            ],
            "update" => [
                "name" => "bail|sometimes|min:3",
                "email" => "bail|sometimes|email|unique:dummy_validate,email"
            ]
        ];
    }

    /**
     * Return the form structure.
     */
    public static function form(): array
    {
        return [];
    }
}
