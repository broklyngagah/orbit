<?php

use Orbit\Machine\Config\Config;
use Orbit\Machine\Config\InvalidConfigTypeException;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    private $config;

    public function setUp()
    {
        $this->config = new Config(null, ROOT_PATH);
    }

    /**
     * Test is config class return an array value.
     * @return bool
     */
    public function testSetupConfigWithArrayResult()
    {
        try {
            $configuration = $this->config
                                ->setupConfig()
                                ->getConfig();

            $this->assertTrue(is_array($configuration));
        } catch(InvalidConfigTypeException $e) {
            $this->assertEquals("Compiled file must be an array.", $e->getMessage());
        }
    }

    /**
     * Test is base path setted
     * @return bool
     */
    public function testIsBasePathIsSetted()
    {
        $base = $this->config->setBasePath(ROOT_PATH)->getBasePath();

        $this->assertFalse(is_null($base));

        $this->assertEquals(ROOT_PATH, $base);
    }

    /**
     * Test is config dir setted
     * @return bool
     */
    public function testIsConfigDirSetted()
    {
        $base = $this->config->setDirectory(ROOT_PATH . '/resources/configs')->getDirectory();

        $this->assertFalse(is_null($base));

        $this->assertEquals(ROOT_PATH . '/resources/configs', $base);
    }

}