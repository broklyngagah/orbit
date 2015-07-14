<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Orbit\Machine\Support\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class CacheClearCommand extends Command
{

    protected $name = 'cache:clear';

    protected $description = 'Clear all cache in application.';

    /**
     * Default Directory on cache path.
     * @var array $directories
     */
    private $directories = [
        'twig',
        'volt',
    ];

    protected function fire()
    {
        $file = new Filesystem;
        $path = di('config')->get('cache.file.path');

        if(! $file->isWritable($path)) {
            $this->showError('Cache folder is not writeable.');
            exit;
        }

        foreach ($this->directories as $dir) {
            $file->deleteDirectory($path . $dir, true);
            $file->put($path . $dir . '/.gitignore', "* \n!.gitignore");
        }

        $this->showInfo('Cache cleared.');

        // logs clear
        if($this->option('logs')) {
            $this->clearLogs($file);
        }

        // logs clear
        if($this->option('apps')) {
            $this->clearApps($file);
        }
    }


    private function clearLogs(Filesystem $file)
    {
        $path = base_path('storages/logs');

        if(! $file->isWritable($path)) {
            $this->showError('Logs folder isn\'t writeable.');
            exit;
        }

        $file->deleteDirectory($path, true);
        $file->put($path . '/.gitignore', "* \n!.gitignore");

        $this->showInfo('Logs cleared.');
    }

    private function clearApps(Filesystem $file)
    {
        $path = base_path('storages/apps');

        if(! $file->isWritable($path)) {
            $this->showError('Apps folder isn\'t writeable.');
            exit;
        }

        $file->deleteDirectory($path, true);
        $file->put($path . '/.gitignore', "* \n!.gitignore");

        $this->showInfo('Compiled apps cleared.');
    }


    protected function getOptions()
    {
        return [
            ['logs', '-l', InputOption::VALUE_NONE, 'Clear log files in [logs] directory too.'],
            ['apps', '-a', InputOption::VALUE_NONE, 'Clear compiled files in [apps] directory too.'],
        ];
    }
}