<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTranslationCommand extends GeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translation {name : The name of the translation to make}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a translation model.';

    /**
     * {@inheritdoc}
     */
    protected $type = 'Translation model';

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/TranslatableModel.stub';
    }
}
