<?php

class Events {

	protected $stack = [];

	public function on($event, $callback) {
		if(false === is_callable($callback)) {
			throw new \InvalidArgumentException('Parameter 2 must be callable.');
		}

		$this->stack[$event][] = $callback;
	}

	public function has($event) {
		return array_key_exists($event, $this->stack);
	}

	public function trigger($event) {
		if(false === $this->has($event)) {
			return false;
		}

		$args = array_slice(func_get_args(), 1);

		foreach($this->stack[$event] as $callback) {
			call_user_func_array($callback, $args);
		}
	}

}
