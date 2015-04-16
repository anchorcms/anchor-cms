<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpSpec extends ObjectBehavior {

	function Let() {
		$env = new \Collection(['REQUEST_URI' => '/projects/index.php/some/path', 'REQUEST_METHOD' => 'PUT']);
		$this->beConstructedWith($env, ['index' => 'index.php', 'base' => '/projects']);
		$this->shouldBeAnInstanceOf('Http');
	}

	function it_should_return_the_current_url() {
		$this->getUri()->shouldReturn('/some/path');
	}

	function it_should_return_the_current_request_method() {
		$this->getMethod()->shouldReturn('PUT');
	}

}
