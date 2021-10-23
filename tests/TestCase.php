<?php

namespace Davesweb\LaravelTranslatable\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Davesweb\LaravelTranslatable\Tests\Models\TestModel;
use Davesweb\LaravelTranslatable\Tests\Models\TestModelTranslation;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * {@inheritdoc}
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpDatabase(): void
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('untranslated')->nullable();
        });

        Schema::create('test_model_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_model_id')->references('id')->on('test_models')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('locale', 8);
            $table->string('name')->nullable();
            $table->text('content')->nullable();
        });

        $id = TestModel::query()->insertGetId([
            'untranslated' => 'Untranslated',
        ]);

        TestModelTranslation::query()->insert([
            'test_model_id' => $id,
            'locale'        => 'nl',
            'name'          => 'Nederlandse naam',
            'content'       => 'Nederlandse inhoud',
        ]);

        TestModelTranslation::query()->insert([
            'test_model_id' => $id,
            'locale'        => 'en',
            'name'          => 'English name',
            'content'       => 'English content',
        ]);
    }
}
