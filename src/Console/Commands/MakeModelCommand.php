<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Illuminate\Console\Command;

class MakeModelCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translatable {model}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a translatable modeland its translation.';
}
