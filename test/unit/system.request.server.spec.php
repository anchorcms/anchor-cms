<?php

use System\request\server;

describe('request\\server', function () {
    beforeEach(function () {
        $this->server = new server([
            'x' => true,
            'y' => 42,
            'z' => 'test'
        ]);
    });

    it('should create an instance', function () {
        expect($this->server)
            ->to->be->an->instanceof(server::class);
    });

    it('should get values from the server array', function () {
        expect($this->server->get('y'))
            ->to->equal(42);
    });

    it('should get a fallback value for a missing key', function () {
        expect($this->server->get('how does a pirate sound like?', 'HARRR!'))
            ->to->equal('HARRR!');
    });

    it('should set a value to the server array', function () {
        $this->server->set('foo', 'bar');

        expect($this->server->get('foo'))
            ->to->equal('bar');
    });

    it('should check whether a key exists in the server array', function () {
        expect($this->server->has('x'))
            ->to->be->true();

        expect($this->server->has('a'))
            ->to->be->false();
    });

    it('should erase values from the server array', function () {
        $this->server->set('123', 321);

        expect($this->server->get('123'))
            ->to->equal(321);

        $this->server->erase('123');

        expect($this->server->get('123'))
            ->to->be->null();
    });
});
