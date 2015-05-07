<?php

class Collection implements Countable, JsonSerializable, IteratorAggregate, Serializable {

	protected $data;

	public function __construct(array $data = []) {
		$this->data = $data;
	}

	public function get($key, $default = null) {
		return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
	}

	public function put($key, $value) {
		$this->data[$key] = $value;
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

	public function serialize() {
		return serialize($this->data);
	}

	public function unserialize($data) {
		$this->data = unserialize($data);
	}

}
