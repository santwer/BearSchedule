<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeLogic extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:logic {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Logic Class';

    protected $type = 'Logic';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Services';
    }


    protected function getStub()
    {
        $stub = null;
        $stub = '/stubs/logic.stub';
        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }


    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        $replace["use {$controllerNamespace}\Services;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
