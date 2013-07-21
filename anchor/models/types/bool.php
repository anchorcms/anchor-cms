<?php namespace Types;

use Type;
use Input;

class Bool extends Type {

	public static $name = 'Bool';

	public static $type = 'bool';

	public function value($default = '') {
		if( ! isset($this->field->value->bool)) {
			return $default;
		}

		return $this->field->value->bool;
	}

	public function html() {
		return '<input id="extend_' . $this->field->key . '" name="extend[' . $this->field->key . ']" type="checkbox"
			value="1" ' . ($this->value() ? 'checked="checked"': '') . '>';
	}

	public function save() {
		$bool = Input::get('extend.' . $this->field->key) ? 0 : 1;

		return Json::encode(compact('bool'));
	}

}