<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Orbit\Machine\CLI\GeneratorTrait;
use Orbit\Machine\Support\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeCommandCommand extends Command
{

	use GeneratorTrait;

    protected $name = 'make:command';

    protected $description = 'Make command class.';

    protected $stub = __DIR__ . '/stubs/command.txt';

    public function fire()
    {
    	$files = new Filesystem;

    	try {
            $name = ucfirst($this->argument('commandName'));

            if(! preg_match('/command/', strtolower($name))) {
            	$name .= 'Command';
            }

            $controller = base_path('app/Command/' . $name) . '.php';

            if(file_exists($controller)) {
                $this->showError('Command class with name \'' . $name . '\' exists.');
                exit;
            }

            $stub = $this->getStub($files);
            $stub = $this->replaceNamespace('Command', $stub);
            $stub = $this->replaceClass($name, $stub);

           	// replace expected properties
           	$stub = str_replace('{{name}}', $this->argument('callName'), $stub);
           	$stub = str_replace('{{description}}', $this->option('description'), $stub);

            $files->put($controller, $stub);

            $this->showInfo("Command class with name [$name] created.");
        } catch(\Exception $e) {
            $this->showError($e->getMessage());
        }
    }

    protected function getArguments()
    {
    	return [
    		['commandName', InputArgument::REQUIRED, 'The class name of command.'],
    		['callName', InputArgument::REQUIRED, 'Name or what you want to call this command.'],
    	];
    }

    protected function getOptions()
    {
    	return [
    		['description', '-d', InputOption::VALUE_REQUIRED, 'Name or what you want to call this command.', '-'],
    	];
    }

}