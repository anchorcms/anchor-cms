<?php

use System\config as originalConfig;
use System\uri as originalUri;

/** @noinspection PhpIncludeInspection */
require_once(SYS . 'helpers' . EXT);

/**
 * Mock Config class
 */
class Config
{
    public static function __callStatic($method, $args)
    {
        return originalConfig::$method(...$args);
    }
}

/**
 * Mock Uri class
 */
class Uri
{
    public static function to(...$args)
    {
        return originalUri::to(...$args);
    }
}

describe('helpers', function () {
    describe('asset', function () {
        it('should get a relative uri to be used with a view', function () {
            expect(asset('style.css'))
                ->to->equal('/style.css');

            originalConfig::set('app.url', 'https://example.com/anchor/');

            expect(asset('js/main.js'))
                ->to->equal('https://example.com/anchor/js/main.js');
        });
    });

    describe('uri_to', function () {
        it('should retrieve the URL to another endpoint', function () {
            originalConfig::set('app.url', 'https://www.anchorcms.com/');

            expect(uri_to('database'))
                ->to->equal('https://www.anchorcms.com/database');

            originalConfig::erase('app.url');

            expect(uri_to('database'))
                ->to->equal('/database');
        });
    });

    /**
     * dd()
     * dd is one of the few methods that are actually "untestable" - due to calling `exit()`.
     */

    /**
     * TODO:
     * noise() currently relies on the random-lib@1.1 from ircmaxell that uses the mcrypt_module_open()
     * function to generate randomness. This function is deprecated and the module needs to be
     * switched out.
     */
    describe('noise', function () {
        it('should generate a random string', function () {
            expect(@noise(10))
                ->to->have->length(10);
        });

        it('should handle negative lengths', function () {
            expect(@noise(-10))
                ->to->be->false;
        });
    });

    describe('normalize', function () {
        it('should replace foreign characters in a string', function () {

            // that string is like, the most German thing ever
            /** @noinspection SpellCheckingInspection */
            expect(normalize('Fährenkapitän Beißbart'))
                ->to->equal('Fahrenkapitan Beisbart');
        });
    });

    describe('e/eq', function () {
        it('should encode HTML to entities while preserving quotes', function () {
            expect(e('<script type="text/h4xx0r">alert("xss!")</script>'))
                ->to->equal('&lt;script type="text/h4xx0r"&gt;alert("xss!")&lt;/script&gt;');

            expect(e('<script type="text/h4xx0r">alert("xss!")</script>', ENT_NOQUOTES))
                ->to->equal('&lt;script type="text/h4xx0r"&gt;alert("xss!")&lt;/script&gt;');
        });

        it('should encode HTML to entities while dropping quotes', function () {
            expect(eq('<script type="text/h4xx0r">alert("xss!")</script>'))
                ->to->equal(
                    '&lt;script type=&quot;text/h4xx0r&quot;&gt;alert(&quot;xss!&quot;)&lt;/script&gt;'
                );

            expect(e('<script type="text/h4xx0r">alert("xss!")</script>', ENT_QUOTES))
                ->to->equal(
                    '&lt;script type=&quot;text/h4xx0r&quot;&gt;alert(&quot;xss!&quot;)&lt;/script&gt;'
                );
        });
    });

    describe('array_flatten', function () {
        it('should flatten an array', function () {
            expect(array_flatten(
                [
                    [
                        'a',
                        'x' => ['b']
                    ],
                    [
                        [
                            ['c']
                        ]
                    ],
                    [
                        [
                            'test' => 10,
                            'foo'  => 20
                        ],
                        []
                    ]
                ], ['10']
            ))
                ->to->loosely->equal([10, 'a', 'b', 'c', 10, 20]);
        });
    });
});
