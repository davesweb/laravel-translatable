<?php

namespace Davesweb\LaravelTranslatable\Services;

use Illuminate\Database\Migrations\MigrationCreator as IlluminateMigrationCreator;

class MigrationCreator extends IlluminateMigrationCreator
{
    protected function getStub($table, $create)
    {
        if (is_null($table)) {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.stub')
                ? $customPath
                : $this->stubPath().'/migration.stub';
        } elseif ($create) {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.create.stub')
                ? $customPath
                : $this->stubPath().'/migration.create.stub';
        } else {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.update.stub')
                ? $customPath
                : $this->stubPath().'/migration.update.stub';
        }
        
        return $this->files->get($stub);
    }
    
    protected function populateStub($name, $stub, $table)
    {
        $stub = str_replace(
            ['DummyClass', '{{ class }}', '{{class}}'],
            $this->getClassName($name), $stub
        );
        
        // Here we will replace the table place-holders with the table specified by
        // the developer, which is useful for quickly creating a tables creation
        // or update migration from the console instead of typing it manually.
        if (! is_null($table)) {
            $stub = str_replace(
                ['DummyTable', '{{ table }}', '{{table}}'],
                $table, $stub
            );
        }
        
        return $stub;
    }
}