<?php

use System\request;

/**
 * Mock $_SERVER variable
 */
$_SERVER = [
    'REQUEST_METHOD'        => 'GET',
    'SERVER_PROTOCOL'       => 'HTTP/1.1',
    'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
];

global $_SERVER;

describe('request', function () {
    it('should get the request method', function () {
        global $_SERVER;

        expect(request::method())
            ->to->equal('GET');
    });

    it('should get the request protocol', function () {
        global $_SERVER;

        expect(request::protocol())
            ->to->equal('HTTP/1.1');
    });

    it('should determine whether the current request is an AJAX request', function () {
        global $_SERVER;

        expect(request::ajax())
            ->to->be->true;

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'Something else';

        expect(request::ajax())
            ->to->be->false;
    });

    it(
        'should determine whether the current request has been created on the command line',
        function () {
            expect(request::cli())
                ->to->be->true;
        }
    );
});
