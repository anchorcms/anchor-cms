<?php

/**
 * Custom Field Type
 */
class Type {

	protected $field;

	public static function create($field_type, $custom_field) {
		$classname = sprintf('\Types\%s', ucfirst($field_type));

		return new $classname($custom_field);
	}

	public function __construct($field) {
		$this->field = $field;
	}

	public function delete() {}

	public static function attributes($input) {}

	public function __get($key) {
		return $this->field->{$key};
	}

}