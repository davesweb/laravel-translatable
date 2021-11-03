<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

class MakeMigrationCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translatable-migration {name : The name of the translatable model}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a migration file for a translatable model and its translation.';

    private Composer $composer;

    public function __construct(Composer $composer)
    {
        parent::__construct();

        $this->composer = $composer;
    }

    public function handle(): int
    {
        $name = $this->argument('name');

        $translatableTable = (string) Str::of($name)->classBasename()->lower()->plural();
        $translationTable  = (string) Str::of($name)->classBasename()->lower()->append('_translations');
        $foreignKeyColumn  = (string) Str::of($name)->classBasename()->lower()->append('_id');
        $filename          = Carbon::now()->format('Y_m_d_His_') . 'create_' . $translationTable . '_table';
        $className         = 'Create' . Str::of($translatableTable)->ucfirst() . 'Table';

        $stub = file_get_contents(__DIR__ . '/../../../stubs/migration.stub');

        $stub = Str::of($stub)->replace([
            '{{ classname }}',
            '{{ translatable_table }}',
            '{{ translations_table }}',
            '{{ foreign_key_column }}',
        ], [
            $className,
            $translatableTable,
            $translationTable,
            $foreignKeyColumn,
        ]);

        file_put_contents(database_path('migrations/' . $filename . '.php'), $stub);

        $this->info('Migration ' . $filename . ' created, dumping autoload files...');

        $this->composer->dumpAutoloads();

        $this->info('Autoload files regenerated.');

        return parent::SUCCESS;
    }
}
