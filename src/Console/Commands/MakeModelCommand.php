<?php

namespace Davesweb\LaravelTranslatable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeModelCommand extends GeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'make:translatable
        {name : The name of the model to make}
        {--t|translation : Also create the translation model}
        {--m|migration : Also create the migration file}
        {--a|all : Add all possible options}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a translatable model and optionally its translation.';

    /**
     * {@inheritdoc}
     */
    protected $type = 'Translatable model';

    public function handle(): int
    {
        if (false === parent::handle()) {
            return self::FAILURE;
        }

        $translationName = $this->getNameInput() . 'Translation';

        if ($this->option('all')) {
            $this->input->setOption('translation', true);
            $this->input->setOption('migration', true);
        }

        if ($this->option('translation')) {
            $this->call('make:translation', ['name' => $translationName]);
        }

        if ($this->option('migration')) {
            $this->call('make:translatable-migration', ['name' => $this->getNameInput()]);
        }

        return self::SUCCESS;
    }

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/Model.stub';
    }
}
