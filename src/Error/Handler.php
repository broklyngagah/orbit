<?php

namespace Orbit\Machine\Error;

use Exception;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;

class Handler
{

	protected $response;

	protected $log;

	protected $dontReport = [];

	protected $debug;

	public function __construct($di, $debug = true)
	{
		$this->log = $di->get('logger');

		$this->response = $di->get('response');

		$this->debug = $debug;

        if(! $debug) {
            ini_set('display_errors', 'Off');
        }
	}

	public function report(Exception $e)
	{
		if ($this->shouldReport($e)) {
            $this->log->error($e->getMessage() . " ::" . $e->getFile() . " line(" . $e->getLine() . ")");
        }
	}

	/**
     * Determine if the exception should be reported.
     *
     * @param  \Exception  $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return !$this->shouldntReport($e);
    }
    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function shouldntReport(Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }
        return false;
    }

    public function render($request, Exception $e)
    {
    	return (new SymfonyDisplayer($this->debug))->sendPhpResponse($e); 
    }

    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }
}