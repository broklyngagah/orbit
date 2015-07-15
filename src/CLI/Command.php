<?php

namespace Orbit\Machine\CLI;

use Orbit\Machine\CLI\Application;
use Symfony\Component\Console\Command\Command as SfCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Commad
 * @package Orbit\Machine\CLI
 * @author  Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class Command extends SfCommand
{

    /**
     * Orbit application console.
     * @var Application
     */
    protected $orbit;

    /**
     * Command name
     * @var srting
     */
    protected $name;

    /**
     * Command description
     * @var string
     */
    protected $description;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface $input
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Input\OutputInterface
     */
    protected $output;

    /**
     * Phalcon dependency injection instance
     * @var \Phalcon\Di
     */
    protected $di;

    public function __construct()
    {
        parent::__construct($this->name);

        $this->setDescription($this->description);

        $this->prepareParameters();
    }

    /**
     * Specify the arguments and options on the command.
     *
     * @return void
     */
    protected function prepareParameters()
    {
        foreach($this->getArguments() as $arguments) {
            call_user_func_array([$this, 'addArgument'], $arguments);
        }

        foreach($this->getOptions() as $options) {
            call_user_func_array([$this, 'addOption'], $options);
        }
    }

    /**
     * Set Phalcon dependency injection.
     *
     * @param mixed
     * @return $this
     */
    public function setDI($di)
    {
        $this->di = $di;

        return $this;
    }

    /**
     * Get the Phalcon dependency injection instance.
     *
     * @param  string $name service name
     * @return \Phalcon\Di|mixed
     */
    public function getDI($name = null)
    {
        $name = $name;

        return null === $name ? $this->di : $this->di->get($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fire();
    }

    /**
     * Run the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;

        $this->output = $output;

        return parent::run($input, $output);
    }

    /**
     * Call another command.
     *
     * @return \Orbit\CLI\Command
     */
    public function call()
    {
        $instance = $this->getApplication()->find($command);

        $arguments['command'] = $command;

        return $instance->run(new ArrayInput($arguments), $this->output);
    }

    /**
     * Call another command silently.
     *
     * @param $command
     * @param array $arguments
     * @return \Orbit\CLI\Command
     * @throws \Exception
     */
    public function callSilent($command, array $arguments = [])
    {
        $instance = $this->getApplication()->find($command);

        $arguments['command'] = $command;

        return $instance->run(new ArrayInput($arguments), new NullOutput);
    }

    /**
     * Show the message output.
     *
     * @param  string $message
     * @param  string $type
     * @return mixed
     */
    protected function showMessage($message, $type = 'info')
    {
        $this->output->writeln("<$type>$message</$type>");
    }

    /**
     * Helper for show the error message.
     *
     * @param  string $message
     * @return mixed
     */
    protected function showError($message)
    {
        $this->showMessage($message, 'error');
    }

    /**
     * Helper for show the info message.
     *
     * @param  string $message
     * @return mixed
     */
    protected function showInfo($message)
    {
        $this->showMessage($message, 'info');
    }

    /**
     * Gets the Orbit application console.
     *
     * @return Application
     */
    public function getOrbit()
    {
        return $this->orbit;
    }

    /**
     * Sets the Orbit application console.
     *
     * @param Application $orbit the orbit
     * @return mixed
     */
    public function setOrbit(Application $orbit)
    {
        $this->orbit = $orbit;

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Get the value of a command argument.
     *
     * @param  string $key
     * @return string|array
     */
    public function argument($key = null)
    {
        if(is_null($key)) return $this->input->getArguments();

        return $this->input->getArgument($key);
    }

    /**
     * Get the value of a command option.
     *
     * @param  string $key
     * @return string|array
     */
    public function option($key = null)
    {
        if(is_null($key)) return $this->input->getOptions();

        return $this->input->getOption($key);
    }
}