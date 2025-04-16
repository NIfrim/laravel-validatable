<?php

namespace Nifrim\LaravelValidatable\Tests;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Support\Facades\File;

abstract class TestCase extends Orchestra
{
    protected static array $destinationData;
    protected FakerGenerator $faker;

    /**
     * @var TTemporalConfig
     */
    protected static $temporalConfig;

    /**
     * @inheritDoc
     */
    public function defineEnvironment($app)
    {
        parent::defineEnvironment($app);
        config()->set('database.default', 'testing');

        // Make faker
        $this->faker = FakerFactory::create();
        static::$destinationData = [
            'title' => $this->faker->name()
        ];

        // Ensure database file exists
        $sqliteFile = __DIR__ . DIRECTORY_SEPARATOR . 'testing.sqlite';
        if (!File::exists($sqliteFile)) {
            touch($sqliteFile);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        static::migrateDirection('up');
    }

    public function tearDown(): void
    {
        static::migrateDirection('down');
        parent::tearDown();
    }

    /**
     * @param up|down $direction
     */
    public static function migrateDirection(string $direction = 'up'): void
    {
        foreach (File::allFiles(__DIR__ . '/Database/Migrations') as $migration) {
            if ($migration->getExtension() === 'php') {
                (include $migration->getRealPath())->{$direction}();
            }
        }
    }
}
