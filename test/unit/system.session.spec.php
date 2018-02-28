<?php

use System\session;

describe('session', function () {
    it('should set session options to the php.ini', function () {
        session::setOptions([
            'name'        => 'Anchor-Unit-Test-Session',
            'cookie_path' => '/jar'
        ]);

        expect(ini_get('session.name'))
            ->to->equal('Anchor-Unit-Test-Session');

        expect(ini_get('session.cookie_path'))
            ->to->equal('/jar');
    });

    it('should start a new session', function () {
        expect(session_status())
            ->to->equal(PHP_SESSION_NONE);

        session::start();

        expect(session_status())
            ->to->equal(PHP_SESSION_ACTIVE);

        session_write_close();
    });

    it('should close a session', function () {
        session::start();

        expect(session_status())
            ->to->equal(PHP_SESSION_ACTIVE);

        session::close();

        expect(session_status())
            ->equal(PHP_SESSION_NONE);
    });

    it('should set session data', function () {
        session::start();

        session::put('foo', 'bar');

        expect($_SESSION['foo'])
            ->to->equal('bar');

        session::close();
    });

    it('should set session data by nested keys', function () {
        session::start();

        session::put('329pgfko.d23oifj', 'quz');

        expect($_SESSION['329pgfko']['d23oifj'])
            ->to->equal('quz');

        session::close();
    });

    it('should get session data', function () {
        session::start();

        session::put('foo', 'bar');

        expect(session::get('foo'))
            ->to->equal('bar');

        session::close();
    });

    it('should get session data by nested keys', function () {
        session::start();

        session::put('pg0j24gp.23f23f', 'quz');

        expect(session::get('pg0j24gp.23f23f'))
            ->to->equal('quz');

        session::close();
    });

    it('should get a fallback value for a missing session key', function () {
        session::start();

        expect(session::get('missing key', 'it\'s something'))
            ->to->equal('it\'s something');

        session::close();
    });

    it('should erase session data keys', function () {
        session::start();

        session::put('test', 'arbitrary data');

        expect(session::get('test'))
            ->to->equal('arbitrary data');

        session::erase('test');

        expect(session::get('test'))
            ->to->be->null();

        session::close();
    });

    it('should regenerate the session ID', function () {
        session::start();

        $previousSessionId = session_id();

        session::regenerate();

        expect(session_id())
            ->to->not->equal($previousSessionId);

        session::close();
    });

    // TODO: passing $destroy=true currently does not clear the session information. Needs to be fixed.
    xit('should regenerate the session ID and destroy the session (will not destroy session)', function () {
        session::start();

        $previousSessionId = session_id();

        session::put('foo', 'bar');

        session::regenerate(true);

        expect(session_id())
            ->to->not->equal($previousSessionId);

        expect(session::get('foo'))
            ->to->be->null();

        session::close();
    });

    it('should flash session data', function () {
        session::flash('test');

        expect(session::get('_in'))
            ->to->equal('test');

        expect(session::flash())
            ->to->loosely->equal([]);

        session::put('_out', 135);

        expect(session::flash())
            ->to->equal(135);
    });
});
