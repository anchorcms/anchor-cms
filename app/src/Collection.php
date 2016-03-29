<?php

class Collection implements Countable, JsonSerializable, IteratorAggregate {

	protected $data;

	public function __construct(array $data = []) {
		$this->data = $data;
	}

	public function get($key, $default = null) {
		return $this->has($key) ? $this->data[$key] : $default;
	}

	public function first($key, $default = null) {
		$value = $this->get($key);

		return is_array($value) ? $value[0] : $default;
	}

	public function last($key, $default = null) {
		$value = $this->get($key);

		if(is_array($value)) {
			$index = count($value) - 1;

			return $value[$index];
		}

		return $default;
	}

	public function put($key, $value) {
		$this->data[$key] = $value;
	}

	public function push($key, $value) {
		if($this->has($key) && ! is_array($this->data[$key])) {
			$this->data[$key] = [$this->data[$key]];
		}

		$this->data[$key][] = $value;
	}

	public function has($key) {
		return array_key_exists($key, $this->data);
	}

	public function remove($key) {
		if($this->has($key)) {
			unset($this->data[$key]);
		}
	}

	public function count() {
		return count($this->data);
	}

	public function jsonSerialize() {
		return $this->data;
	}

	public function getIterator() {
		return new ArrayIterator($this->data);
	}

}
