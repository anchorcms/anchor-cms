<?php

use System\config;

describe('config', function () {
    it('should retrieve a value from the config', function () {
        expect(config::get('error.report'))
            ->to->be->true;
    });

    it('should use the filename shorthand to access config keys', function () {
        expect(config::error('report'))
            ->to->be->true;
    });

    it('should get a fallback value for a missing key', function () {
        expect(config::get('missing key', 'i did not want that'))
            ->to->equal('i did not want that');
    });

    it('should set new values to the config', function () {
        config::set('foo', 42);

        expect(config::get('foo'))
            ->to->equal(42);
    });

    it('should set new nested values to the config', function () {
        config::set('this.is.a.test', 1.25);
        config::set('this.is.another.test', 5.12);

        expect(config::get('this.is.a.test'))
            ->to->equal(1.25);

        expect(config::get('this.is.another.test'))
            ->to->equal(5.12);
    });

    it('should delete values', function () {
        config::erase('error.report');

        expect(config::error('report'))
            ->to->be->null;
    });
});
