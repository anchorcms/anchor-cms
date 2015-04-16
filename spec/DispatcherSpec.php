<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DispatcherSpec extends ObjectBehavior {

	function Let() {
		$routes = ['/home' => function() { return 'Hello World'; }];
		$namespace = '\\spec';
		$events = new \Events;
		$container = new \Container;

		$this->beConstructedWith($routes, $namespace, $events, $container);
		$this->shouldBeAnInstanceOf('Dispatcher');
	}

	function it_should_match_a_uri() {
		$this->match('/home')->shouldReturn('Hello World');
	}

}
