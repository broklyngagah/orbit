<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Orbit\Machine\CLI\GeneratorTrait;
use Orbit\Machine\Support\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MakeControllerCommand extends Command
{

    use GeneratorTrait;

    protected $name = 'make:controller';

    protected $description = 'Make controller class.';

    protected $stub = __DIR__ . '/stubs/controller.txt';

    protected function fire()
    {
        $files = new Filesystem;

        try {
            $name = ucfirst($this->argument('name'));

            $controller = base_path('app/Controller/' . $name) . '.php';

            if(file_exists($controller)) {
                $this->showError('Controller with name \'' . $name . '\' exists.');
                exit;
            }

            $stub = $this->getStub($files);
            $stub = $this->replaceNamespace('Controller', $stub);
            $stub = $this->replaceClass($name, $stub);

            $files->put($controller, $stub);

            $this->showInfo("Controller wiht name [$name] created.");
        } catch (\Exception $e) {
            $this->showError($e->getMessage());
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
        ];
    }
}