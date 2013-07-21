<?php namespace Types;

use Type;
use Input;
use Json;

class Text extends Type {

	public static $name = 'Text';

	public static $type = 'text';

	public function value($default = '') {
		if( ! isset($this->field->value->text)) {
			return $default;
		}

		return $this->field->value->text;
	}

	public function html() {
		return '<input id="extend_' . $this->field->key . '" name="extend[' . $this->field->key . ']"
			type="text" value="' . $this->value() . '">';
	}

	public function save() {
		$text = Input::get('extend.' . $this->field->key);

		return Json::encode(compact('text'));
	}

}