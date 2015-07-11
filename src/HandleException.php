<?php 

namespace Orbit\Machine;

use ErrorException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Debug\Exception\FatalErrorException;

class HandleException
{

	protected $di;

	public function __construct($di)
	{

		$this->di = $di;

		error_reporting(-1);

		set_error_handler([$this, 'handleError']);

		set_exception_handler([$this, 'handleException']);

		register_shutdown_function([$this, 'handleShutdown']);
	}

	public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    public function handleException($e)
    {
        $this->getExceptionHandler()->report($e);
        if (preg_match('/cli/', php_sapi_name())) {
            $this->renderForConsole($e);
        } else {
            $this->renderHttpResponse($e);
        }
    }

    protected function renderForConsole($e)
    {
        $this->getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
    }

    protected function renderHttpResponse($e)
    {
        $this->getExceptionHandler()->render($this->di['request'], $e);//->send();
    }

    public function handleShutdown()
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalExceptionFromError($error, 0));
        }
    }

    protected function fatalExceptionFromError(array $error, $traceOffset = null)
    {
        return new FatalErrorException(
            $error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset
        );
    }

    protected function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    protected function getExceptionHandler()
    {
        return new \Orbit\Machine\Error\Handler($this->di);
    }

}