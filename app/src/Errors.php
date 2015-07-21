<?php

class Errors {

	protected $handlers;

	public function __construct() {
		$this->handlers = new \SplObjectStorage;
	}

	public function register() {
		set_error_handler([$this, 'native']);
		set_exception_handler([$this, 'exception']);
		register_shutdown_function([$this, 'shutdown']);
	}

	public function handler($callback) {
		$this->handlers->attach($callback);
	}

	public function exception($exception) {
		foreach($this->handlers as $handler) {
			$handler($exception);
		}

		$this->halt();
	}

	public function halt() {
		exit(1);
	}

	public function native($code, $message, $file, $line) {
		if($code & error_reporting()) {
			$this->exception(new \ErrorException($message, $code, 0, $file, $line));
		}

		return false;
	}

	public function shutdown() {
		if($error = error_get_last()) {
			extract($error);

			$this->native($type, $message, $file, $line);
		}
	}

	public function normalise($frame) {
		$defaults = [
			'file' => '[internal function]',
			'line' => 0,
			'function' => 'Unknown',
			'class' => '',
			'type' => '',
			'args' => [],
		];

		return array_merge($defaults, $frame);
	}

	public function frame(array $params) {
		$params = $this->normalise($params);

		$params['context'] = $this->context($params['file'], $params['line']);

		return $params;
	}

	public function context($file, $line, $padding = 3) {
		$html = '';

		if(false === is_file($file)) {
			return $html;
		}

		$lines = file($file);

		if(count($lines) > ($padding * 2)) {
			$context = array_slice($lines, $line - $padding - 1, $padding * 2 + 1);
			$start = $line - $padding;
		}
		else {
			$context = $lines;
			$start = 1;
		}

		foreach($context as $index => $str) {
			$num = $start + $index;
			$class = $num == $line ? 'highlight' : '';

			$html .= sprintf('<pre class="%s">%d. %s</pre>', $class, $num, htmlspecialchars(rtrim($str)));
		}

		unset($lines);

		unset($context);

		return $html;
	}

}
