<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior {

	function Let() {
		$items = ['foo' => 'bar', 'baz' => 'qux'];
		$this->beConstructedWith($items);
		$this->shouldBeAnInstanceOf('Collection');
	}

	function it_should_contain_constructed_items() {
		$this->count()->shouldReturn(2);
	}

	function it_should_be_able_to_test_a_item_for_existance_by_key() {
		$this->has('foo')->shouldReturn(true);
	}

	function it_should_be_able_to_get_a_item_by_key() {
		$this->get('foo')->shouldReturn('bar');
	}

	function it_should_be_able_to_remove_a_item_by_key() {
		$this->remove('foo');
		$this->count()->shouldReturn(1);
		$this->has('foo')->shouldReturn(false);
	}

	function it_should_accept_new_items() {
		$this->put('foo', 'bar');
		$this->count()->shouldReturn(2);
		$this->has('foo')->shouldReturn(true);
	}

	function it_should_fallback_on_a_default() {
		$this->get('miss', 'default')->shouldReturn('default');
	}

}
