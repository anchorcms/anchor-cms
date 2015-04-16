<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsSpec extends ObjectBehavior {

	function Let() {
		$this->shouldBeAnInstanceOf('Events');
	}

	function it_should_accept_a_new_event() {
		$this->on('dispatch', function($param) { $param = 1; });
		$this->has('dispatch')->shouldReturn(true);
		$this->trigger('dispatch', $param = 0);
	}

}
