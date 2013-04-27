<?php

class Layout {

	public static function create($path, $vars = array()) {
		return View::create($path, $vars)
			->partial('header', 'partials/header', $vars)
			->partial('footer', 'partials/footer', $vars);
	}

}