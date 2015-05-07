<?php

class Container implements ArrayAccess {

	private $values;

	public function __construct(array $values = []) {
		$this->values = $values;
	}

	public function offsetExists($key) {
		return isset($this->values[$key]);
	}

	public function offsetUnset($key) {
		unset($this->values[$key]);
	}

	public function offsetGet($key) {
		if( ! isset($this->values[$key])) {
			throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined in container.', $key));
		}

		// evaluate closures
		if($this->values[$key] instanceof Closure) {
			$this->values[$key] = $this->values[$key]($this);
		}

		return $this->values[$key];
	}

	public function offsetSet($key, $value) {
		$this->values[$key] = $value;
	}

}
