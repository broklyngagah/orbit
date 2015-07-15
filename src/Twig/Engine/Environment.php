<?php

namespace Orbit\Machine\Twig\Engine;

use Phalcon\DiInterface;

class Environment extends \Twig_Environment
{
    /**
     * Internal dependency injector.
     *
     * @var \Phalcon\DiInterface
     */
    protected $di = null;

    /**
     * {@inheritdoc}
     *
     * @param \Phalcon\DiInterface $di
     * @param \Twig_LoaderInterface $loader
     * @param array $options
     */
    public function __construct(DiInterface $di, \Twig_LoaderInterface $loader = null, $options = [])
    {
        $this->di = $di;
        parent::__construct($loader, $options);
    }

    /**
     * Returns the internal dependency injector.
     *
     * @return \Phalcon\DiInterface
     */
    public function getDi()
    {
        return $this->di;
    }
}