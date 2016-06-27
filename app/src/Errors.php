<?php

namespace Anchorcms;

use Throwable;
use ErrorException;

class Errors
{
    protected $handlers;

    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    public function register()
    {
        set_error_handler([$this, 'native']);
        set_exception_handler([$this, 'exception']);
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function handler(callable $callback)
    {
        $this->handlers[] = $callback;
    }

    public function matches(Throwable $exception, callable $handler): bool
    {
        // reflect on the handler
        $reflection = new \ReflectionFunction($handler);

        // no params to check typehint
        if ($reflection->getNumberOfParameters() === 0) {
            return false;
        }

        // get the first
        $param = $reflection->getParameters()[0];

        // does the first param have a type
        if (false === $param->hasType()) {
            return false;
        }

        // invoke toString method on ReflectionType class
        $type = (string) $param->getType();

        return $exception instanceof $type;
    }

    public function exception(Throwable $exception)
    {
        // filter handlers that match the exception
        $handlers = array_filter($this->handlers, function ($handler) use ($exception) {
            return $this->matches($exception, $handler);
        });

        // invoke filtered handlers
        foreach ($handlers as $handler) {
            $handler($exception);
        }
    }

    public function native(string $code, string $message, string $file, int $line)
    {
        if ($code & error_reporting()) {
            $this->exception(new ErrorException($message, $code, 0, $file, $line));
        }

        return false;
    }
}
