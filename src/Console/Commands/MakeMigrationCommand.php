<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Illuminate\Console\Command;

class MakeMigrationCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translatable-migration {migration}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a migration file for a translatable model and its translation.';
}
