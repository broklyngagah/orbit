<?php


use Phalcon\DI;
//use \Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{

    protected $di;

    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(Phalcon\DiInterface $di = null, Phalcon\Config $config = null)
    {
        // Load any additional services that might be required during testing
        $this->di = DI::getDefault();

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}
