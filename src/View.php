<?php

class View {

	protected $template;

	protected $vars;

	public function __construct($template, array $vars = []) {
		if(false === is_file($template)) {
			throw new ErrorException(sprintf('Template file does not exists: %s', $template));
		}

		$this->template = $template;
		$this->vars = $vars;
	}

	public function __set($key, $value) {
		$this->vars[$key] = $value;
	}

	public function __get($key) {
		if(false === array_key_exists($key, $this->vars)) {
			throw new ErrorException(sprintf('Undefined index: %s', $key));
		}

		return $this->vars[$key];
	}

	public function render(array $vars = []) {
		ob_start();

		extract($vars);

		require $this->template;

		return ob_get_clean();
	}

}
