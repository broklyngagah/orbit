<?php

namespace Orbit\Machine\CLI;

use Orbit\Machine\Support\Filesystem;

trait GeneratorTrait
{

    protected function getStub(Filesystem $filesystem)
    {
        return $filesystem->get($this->stub);
    }

    protected function getAppNamespace()
    {
        return di('config')->loader->default_namespace;
    }

    protected function replaceClass($name, $stub)
    {
        return str_replace('{{class}}', $name, $stub);
    }

    protected function replaceNamespace($name, $stub)
    {
        $name = $this->getAppNamespace() . '\\' . $name;

        return str_replace('{{namespace}}', $name, $stub);
    }

    protected function replaceDescription($name, $stub)
    {
        $name = $this->getAppNamespace() . '\\' . $name;

        return str_replace('{{description}}', $name, $stub);
    }
}