<?php

namespace Nifrim\LaravelValidatable\Tests\Validators\DummyValidate;

use Nifrim\LaravelValidatable\Contracts\Validator;

class Other implements Validator
{
    /**
     * @inheritDoc
     */
    public static function messages(): array
    {
        return [
            "create" => [],
            "update" => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        return [
            "create" => [
                "name" => "bail|required|min:3",
            ],
            "update" => [
                "name" => "bail|sometimes|min:3",
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public static function form(): array
    {
        return [];
    }
}
