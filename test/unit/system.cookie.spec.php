<?php

use System\cookie;

describe('cookie', function () {
    it('should write a cookie', function () {
        cookie::write('test', 'foo');

        expect(cookie::$bag)
            ->to->loosely->equal([
                'test' => [
                    'name'       => 'test',
                    'value'      => 'foo',
                    'expiration' => 0,
                    'path'       => '/',
                    'domain'     => null,
                    'secure'     => null,
                    'HttpOnly'   => true
                ]
            ]);

        cookie::write(
            'test-2',
            'bar',
            216000,
            '/anchor',
            'example.com',
            true,
            false
        );

        expect(cookie::$bag)
            ->to->have->property('test-2')
            ->and->to->have->deep->property('expiration')
            ->that->is->above(216000);

        expect(cookie::$bag)
            ->to->have->property('test-2')
            ->and->to->have->deep->property('path', '/anchor');

        expect(cookie::$bag)
            ->to->have->property('test-2')
            ->and->to->have->deep->property('domain', 'example.com');

        expect(cookie::$bag)
            ->to->have->property('test-2')
            ->and->to->have->deep->property('secure', true);

        expect(cookie::$bag)
            ->to->have->property('test-2')
            ->and->to->have->deep->property('HttpOnly', false);
    });

    it('should read a cookie from the cookie bag', function () {
        cookie::write('test-3', 'baz');

        expect(cookie::read('test-3'))
            ->to->equal('baz');
    });

    it('should read a cookie from the global cookies', function () {
        $_COOKIE['glob'] = 'quz';

        expect(cookie::read('glob'))
            ->to->equal('quz');
    });

    it('should return a fallback value for missing cookies', function () {
        expect(cookie::read('missing key', 'I needed this desperately!'))
            ->to->equal('I needed this desperately!');
    });

    it('should erase cookies from the cookie bag', function () {
        cookie::write('test-4', 'foo bar baz');

        expect(cookie::read('test-4'))
            ->to->equal('foo bar baz');

        cookie::erase('test-4');

        expect(cookie::read('test-4'))
            ->to->be->null;
    });
});
