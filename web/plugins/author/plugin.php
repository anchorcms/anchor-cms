<?php

namespace Plugins\Author;

class Plugin extends \Plugins {

	protected $name = 'Author';

	protected $desc = 'Creates a author profile page';

	public function init($events) {
		$events->listen('route', [$this, 'route']);
	}

	public function route($request) {}

}
