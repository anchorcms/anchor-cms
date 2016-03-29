<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SlugifySpec extends ObjectBehavior {

	public function it_is_initializable() {
		$this->shouldHaveType('Slugify');
	}

	public function it_should_trim_dashes() {
		$this->slug('This is a test ---')->shouldEqual('this-is-a-test');
	}

	public function it_should_remove_invalid_characters() {
		$this->slug('This \' is a ## test')->shouldEqual('this-is-a-test');
	}

	public function it_should_lowercase_cyrillic_characters() {
		$this->slug('Компьютер')->shouldEqual('компьютер');
	}

	public function it_should_ignore_logograms() {
		$this->slug('電腦')->shouldEqual('電腦');
	}

}
