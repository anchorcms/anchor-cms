<?php namespace Types;

use Type;
use Input;
use Json;
use Markdown;

class Html extends Type {

	public static $name = 'Html';

	public static $type = 'html';

	public function value($default = '') {
		if( ! isset($this->field->value->html)) {
			return $default;
		}

		return Markdown::defaultTransform($this->field->value->html);
	}

	public function html() {
		return '<textarea id="extend_' . $this->field->key . '"
			name="extend[' . $this->field->key . ']" type="text">' . $this->value() . '</textarea>';
	}

	public function save() {
		$html = Input::get('extend.' . $this->field->key);

		return Json::encode(compact('html'));
	}

}