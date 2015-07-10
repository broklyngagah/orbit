<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Orbit\Machine\Support\Str;
use Symfony\Component\Console\Input\ArrayInput;

class GenerateKeyCommand extends Command
{
    protected $name = 'key:generate';

    protected $description = 'Generate new key for this application.';

    public function fire()
    {
        $defaultKey = di('config')->app->key;

        $key = Str::random(32);

        $path = base_path('.env');

        if(file_exists($path)) {
            file_put_contents($path, str_replace(
                $defaultKey, $key, file_get_contents($path)
            ));

            // set the new application key to config service.
            di('config')->app->key = $key;
        }

        $this->callOptimize();

        $this->showInfo($key);
    }

    private function callOptimize()
    {
        $this->callSilent('optimize');
    }
}