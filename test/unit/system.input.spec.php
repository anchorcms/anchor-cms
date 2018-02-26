<?php

use System\input;
use System\session;

describe('input', function () {
    it('should detect query string params', function () {
        $_SERVER['REQUEST_URI'] = '/?foo=bar&baz=true';

        input::detect('GET');

        expect(input::$array['foo'])
            ->to->equal('bar');

        expect(input::$array['baz'])
            ->to->equal('true');

        unset($_SERVER['REQUEST_URI']);
    });

    it('should detect request body params', function () {
        $_POST['course'] = 'galapagos islands';

        input::detect('POST');

        expect(input::$array['course'])
            ->to->equal('galapagos islands');

        unset($_POST['course']);
    });

    xit('should detect php://input params (currently no possible way to modify the body)');

    it('should retrieve a param', function () {
        $_POST['course'] = 'easter islands';

        input::detect('POST');

        expect(input::get('course'))
            ->to->equal('easter islands');

        unset($_POST['course']);
    });

    it('should return a fallback for a missing param', function () {
        $_POST['course'] = 'midway islands';

        input::detect('POST');

        expect(input::get('destination', 'edge of the oceans'))
            ->to->equal('edge of the oceans');

        unset($_POST['course']);
    });

    it('should retrieve an array of params', function () {
        $_POST['course']     = 'india';
        $_POST['origin']     = 'ye olde world';
        $_POST['crew_count'] = '73';

        input::detect('POST');

        expect(input::get_array(['course', 'origin', 'crew_count']))
            ->to->loosely->equal([
                'course'     => 'india',
                'origin'     => 'ye olde world',
                'crew_count' => '73'
            ]);

        unset($_POST['course']);
        unset($_POST['origin']);
        unset($_POST['crew_count']);
    });

    it('should retrieve an array of params with a fallback value', function () {
        $_POST['ship_type'] = 'three-master';

        input::detect('POST');

        expect(input::get_array(
            ['ship_type', 'pirate_encounters', 'days_travelled'],
            'error: log book not found!'
        ))
            ->to->loosely->equal([
                'ship_type'         => 'three-master',
                'pirate_encounters' => 'error: log book not found!',
                'days_travelled'    => 'error: log book not found!'
            ]);

        unset($_POST['ship_type']);
    });

    it('should flash the input data to the session', function () {
        $_POST['foo'] = 'test';

        input::detect('POST');
        input::flash();

        expect(session::get('_in'))
            ->to->have->property('foo')
            ->that->equal('test');

        unset($_POST['foo']);
    });

    it('should retrieve the previously flashed input', function () {
        session::put('_out', [
            'foo' => 'test'
        ]);

        expect(input::previous('foo'))
            ->to->equal('test');
    });

    it('should return a fallback for a previously flashed input missing key', function () {
        expect(input::previous('2349uigihufn', 'not there'))
            ->to->equal('not there');
    });
});
