# A Laravel package that extends Eloquent's model functionality to support validatable models.

## Installation

You can install the package via composer:

```bash
composer require nifrim/laravel-validatable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="validatable-config"
```

This is the contents of the published config file:

```php
return [
    'namespace' => 'App\\Validators'
];
```

## Usage

### 1. Make Your Model Validatable

Add the `ValidatableAttributes` trait to any Eloquent model that you want to validate automatically. You can also override the `shouldValidate` method to control if and when validation should run.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nifrim\LaravelValidatable\Concerns\ValidatableAttributes;

class Product extends Model
{
    use ValidatableAttributes;

    // Allow mass assignment on these fields.
    protected $fillable = ['name', 'price', 'description'];

    /**
     * Optionally determine if this model should be validated.
     *
     * @return bool
     */
    public function shouldValidate(): bool
    {
        return true;
    }
}
```

### 2. Create a Validator

Create a validator class that implements the `Nifrim\LaravelValidatable\Contracts\Validator` interface.

By default, the package will look for validator classes in the namespace set in your configuration (default: `App\Validators`). For instance, create a `Product` validator (class name same as the model) in `app/Validators`:

```php
<?php

namespace App\Validators;

use Nifrim\LaravelValidatable\Contracts\Validator;

class Product implements Validator
{
    /**
     * Validation rules for creating and updating.
     */
    public static function rules(): array
    {
        return [
            'create' => [
                'name'  => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
            ],
            'update' => [
                'name'  => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public static function messages(): array
    {
        return [
            'create' => [
                'name.required'  => 'The product name is required.',
                'price.required' => 'The product price is required.',
            ],
            'update' => [
                'name.required'  => 'The product name is required when updating.',
                'price.required' => 'The product price is required when updating.',
            ],
        ];
    }

    /**
     * Define the form structure for building a UI. (optional: can return empty array)
     */
    public static function form(): array
    {
        return [
            'name'  => ['type' => 'text', 'label' => 'Name'],
            'price' => ['type' => 'number', 'label' => 'Price'],
        ];
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nicolae Ifrim](https://github.com/nifrim)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
