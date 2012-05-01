<?php defined('IN_CMS') or die('No direct access allowed.');

class Items implements Iterator {

	private $position = 0;
	private $items = array();   

	public function __construct($array) {
		$this->position = 0;
		$this->items = $array;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function current() {
		return $this->items[$this->position];
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function valid() {
		return isset($this->items[$this->position]);
	}
	
	public function length() {
		return count($this->items);
	}

	public function first() {
		return isset($this->items[0]) ? $this->items[0] : false;
	}

	public function last() {
		$index = count($this->items) - 1;
		return isset($this->items[$index]) ? $this->items[$index] : false;
	}

}
