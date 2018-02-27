<?php

use System\error;

/**
 * MockError class
 */
class MockError
{
    private $message;

    private $file;

    private $line;

    private $trace;

    public function __construct($message, $file = '', $line = 0)
    {
        $this->message = $message;
        $this->file    = $file;
        $this->line    = 0;
        $this->trace   = debug_backtrace();
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getTrace()
    {
        return $this->trace;
    }
}

describe('error', function () {
    #  set_exception_handler([error::class, 'exception']);
    #  set_error_handler([error::class, 'native']);
    #  register_shutdown_function([error::class, 'shutdown']);

    xit('should handle exceptions (not testable due to exit(1) call)');
    xit('should handle errors (not testable due to exit(1) call)');

    it('should log errors', function () {
        ob_start();

        error::log(new MockError('test'));

        $output = ob_get_clean();

        expect(preg_replace('/\s+/', '', $output))
            ->to->equal(preg_replace('/\s+/', '', '<pre>test
     The error has been logged in /anchor/errors.log</pre>'));

        expect(file_exists(APP . 'errors.log'))
            ->to->be->true();

        unlink(APP . 'errors.log');
    });
});
