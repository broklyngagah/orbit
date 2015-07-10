<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommandCommand extends Command
{

    protected $name = 'make:command';

    protected $description = 'Make command class.';

    protected $stub = __DIR__ . '/stubs/command.txt';

    public function fire()
    {
    }
}