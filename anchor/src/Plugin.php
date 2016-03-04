<?php

abstract class Plugin {

	protected $container;

	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

	protected function render($template, array $vars = []) {
		ob_start();

		extract($vars);

		require $template;

		return ob_get_clean();
	}

	abstract public function init();

}
