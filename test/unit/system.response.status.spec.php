<?php

# we'll move to the System\response namespace briefly
# to override the header() function here
namespace System\response;

use function expect;

/**
 * Mock header function - Send a raw HTTP header
 *
 * @param string $header header string
 *
 * @return void
 */
function header($header)
{
    global $__headers;

    array_push($__headers, (string)$header);
}

describe('response\\status', function () {
    it('should create an instance', function () {
        expect(new status())
            ->to->be->an->instanceof(status::class);
    });

    it('should create an instance using the shorthand', function () {
        expect(status::create())
            ->to->be->an->instanceof(status::class);
    });

    it('should set the status code', function () {
        expect(status::create(204)->status)
            ->to->equal(204);
    });

    it('should set the correct status message', function () {
        global $__headers;

        $__headers      = [];
        $statusMessages = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded'
        ];

        foreach ($statusMessages as $code => $message) {
            status::create($code)->header();

            expect(array_pop($__headers))
                ->to->equal("HTTP/1.1 $code $message");
        }
    });
});

