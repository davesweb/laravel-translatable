<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Davesweb\LaravelTranslatable\Services\MigrationCreator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class MakeMigrationCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translatable-migration {name : The name of the migration}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a migration file for a translatable model and its translation.';
    
    private MigrationCreator $creator;
    
    private Composer $composer;
    
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct();
        
        $this->creator = $creator;
        $this->composer = $composer;
    }
    
    public function handle(Filesystem $files): int
    {
        $name = Str::snake(trim($this->input->getArgument('name')));
    
        [$table, $create] = TableGuesser::guess($name);
    
        $this->writeMigration($name, $table, $create);
    
        $this->composer->dumpAutoloads();
        
        return parent::SUCCESS;
    }
    
    protected function writeMigration($name, $table, $create)
    {
        $file = $this->creator->create(
            $name, $this->getMigrationPath(), $table, $create
        );
        
        if (! $this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }
        
        $this->line("<info>Created Migration:</info> {$file}");
    }
    
    protected function getMigrationPath()
    {
        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return ! $this->usingRealPath()
                ? $this->laravel->basePath().'/'.$targetPath
                : $targetPath;
        }
        
        return parent::getMigrationPath();
    }
    
    private function getStubContents(Filesystem $files): string
    {
        $stub = __DIR__ . '../../../stubs/migration.stub';
        
        return $files->get($stub);
    }
}
