<?php

use Orbit\Machine\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    private $configFiles = [
        '/resources/configs/app.php',
        '/resources/configs/cache.php',
        '/resources/configs/command.php',
        '/resources/configs/database.php',
        '/resources/configs/event.php',
        '/resources/configs/loader.php',
        '/resources/configs/queue.php',
        '/resources/configs/router.php',
        '/resources/configs/session.php',
        '/resources/configs/view.php',
    ];

    public function testConfigAppFileExist()
    {
        $c = new Config;

        foreach ($this->configFiles as $file) {
            $c->setDirectory(base_path() . $file);
            $this->assertFileExists($c->getDirectory());
        }
    }
}