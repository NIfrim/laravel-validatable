<?php

namespace Nifrim\LaravelValidatable\Tests;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Nifrim\LaravelValidatable\Tests\Models\DummyValidate;
use Nifrim\LaravelValidatable\Tests\Models\DummyNonValidate;
use Nifrim\LaravelValidatable\Tests\Models\DummyValidate\Other;

#[Group('validatable')]
class ValidatableAttributesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::createFromTimeString('2025-01-01 00:00'));
        config()->set('validatable.namespace', 'Nifrim\\LaravelValidatable\\Tests\\Validators');
    }

    /**
     * Data provider for testing creates.
     */
    public static function provideCreateCases()
    {
        return [
            'model without validator' => [
                'modelClass' => DummyNonValidate::class,
                'createInput' => ['name' => 'No validator'],
                'expectRecord' => true,
                'expectException' => null,
            ],

            'model with validator and valid data' => [
                'modelClass' => DummyValidate::class,
                'createInput' => ['name' => 'John Doe', 'email' => 'john@example.com'],
                'expectRecord' => true,
                'expectException' => null,
            ],

            'model with validator and invalid data' => [
                'modelClass' => DummyValidate::class,
                'createInput' => ['name' => 'John Doe', 'email' => 'invalid'],
                'expectRecord' => false,
                'expectException' => ValidationException::class,
            ],

            'related without validator' => [
                'modelClass' => Other::class,
                'createInput' => ['name' => 'Jane Doe'],
                'expectRecord' => true,
                'expectException' => null,
            ],

            'related with validator and invalid data' => [
                'modelClass' => Other::class,
                'createInput' => ['name' => '1'],
                'expectRecord' => false,
                'expectException' => ValidationException::class,
            ],
        ];
    }

    #[Group('validatable.create')]
    #[DataProvider('provideCreateCases')]
    public function test_create(string $modelClass, array $createInput, bool $expectRecord, ?string $expectException)
    {
        // Arrange: Expect exception
        if ($expectException) {
            $this->expectException($expectException);
        }

        // Act: create the model instance
        $record = $modelClass::create($createInput);

        // Assert: Record has been created
        if ($expectRecord) {
            $this->assertTrue($record->exists);
        }
    }

    public static function provideUpdateCases()
    {
        return [
            'model without validator' => [
                'modelClass'     => DummyNonValidate::class,
                'createInput'    => ['name' => 'No validator'],
                'updateInput'    => ['name' => 'changed'],
                'expectedValue'  => 'changed',
                'expectException' => null,
            ],

            'model with validator and valid data' => [
                'modelClass'     => DummyValidate::class,
                'createInput'    => ['name' => 'John Doe', 'email' => 'john@example.com'],
                'updateInput'    => ['email' => 'changed@example.com'],
                'expectedValue'  => 'changed@example.com',
                'expectException' => null,
            ],

            'model with validator and invalid data' => [
                'modelClass'     => DummyValidate::class,
                'createInput'    => ['name' => 'John Doe', 'email' => 'john@example.com'],
                'updateInput'    => ['email' => 'invalid'],
                'expectedValue'  => null, // Not used when exception is expected.
                'expectException' => ValidationException::class,
            ],

            'related with validator and valid data' => [
                'modelClass'     => Other::class,
                'createInput'    => ['name' => 'Jane Doe'],
                'updateInput'    => ['name' => 'changed'],
                'expectedValue'  => 'changed',
                'expectException' => null,
            ],

            'related with validator and invalid data' => [
                'modelClass'     => Other::class,
                'createInput'    => ['name' => 'Jane Doe'],
                'updateInput'    => ['name' => '1'],
                'expectedValue'  => null, // Not used when exception is expected.
                'expectException' => ValidationException::class,
            ],
        ];
    }

    #[Group('validatable.update')]
    #[DataProvider('provideUpdateCases')]
    public function test_update(string $modelClass, array $createInput, array $updateInput, $expectedValue, ?string $expectException)
    {
        // Arrange: create the initial record, and set exception expectation.
        $record = $modelClass::create($createInput);

        if ($expectException !== null) {
            $this->expectException($expectException);
        }

        // Act: update the record with the provided data.
        $record->update($updateInput);

        // Assert: if no exception is expected, verify that the updated field holds the expected value.
        if ($expectException === null) {
            $field = array_key_first($updateInput);
            $this->assertEquals($expectedValue, $record->$field, "The field [{$field}] did not update as expected.");
        }
    }
}
