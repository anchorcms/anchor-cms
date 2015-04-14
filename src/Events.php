<?php

class Events {

	protected $stack = [];

	public function on($event, $callback) {
		$this->stack[$event][] = $callback;
	}

	public function has($event) {
		return array_key_exists($event, $this->stack);
	}

	public function trigger($event) {
		if( ! $this->has($event)) return;

		foreach($this->stack[$event] as $callback) {
			$callback(array_slice(func_get_args(), 1));
		}
	}

	public function triggerMerge($event) {
		if( ! $this->has($event)) return;

		$output = [];

		foreach($this->stack[$event] as $callback) {
			$output = array_merge($output, $callback(array_slice(func_get_args(), 1)));
		}

		return $output;
	}

	public function triggerOutput($event) {
		if( ! $this->has($event)) return;

		$output = '';

		foreach($this->stack[$event] as $callback) {
			$output .= $callback(array_slice(func_get_args(), 1));
		}

		return $output;
	}

}
