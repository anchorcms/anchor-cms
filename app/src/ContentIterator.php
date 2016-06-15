<?php

namespace Anchorcms;

class ContentIterator implements Countable, Iterator {

	protected $index = -1;

	protected $items;

	public function __construct(array $items = []) {
		$this->items = $items;
	}

	public function __get($key) {
		$item = $this->current();

		return $item->$key;
	}

	public function __call($method, array $args) {
		$item = $this->current();

		return call_user_func_array([$item, $method], $args);
	}

	public function attach($item) {
		$this->items[] = $item;
	}

	public function count() {
		return count($this->items);
	}

	public function first() {
		return $this->items[0];
	}

	public function last() {
		return $this->items[$this->count() - 1];
	}

	public function key() {
		return $this->index;
	}

	public function current() {
		return $this->items[max(0, $this->index)];
	}

	public function valid() {
		return isset($this->items[$this->index]);
	}

	public function next() {
		$this->index++;
	}

	public function rewind() {
		$this->index = 0;
	}

	public function loop() {
		$this->next();

		return $this->valid();
	}

}
