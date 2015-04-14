<?php

class View {

	protected $template;

	protected $vars;

	public function __construct($template) {
		$this->template = $template;
	}

	public function nest($name, $template, array $vars = []) {
		$view = new static($template);

		$this->vars[$name] = $view->render(array_merge($this->vars, $vars));
	}

	public function render(array $vars = []) {
		ob_start();

		extract($vars);

		require $this->template;

		return ob_get_clean();
	}

}
