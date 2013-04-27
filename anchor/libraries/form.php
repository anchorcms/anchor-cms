<?php

class Form {

	public static function open($action, $method = 'POST', $attributes = array()) {
		$attributes['method'] =  static::method(strtoupper($method));

		$attributes['action'] = static::action($action);

		if ( ! array_key_exists('accept-charset', $attributes)) {
			$attributes['accept-charset'] = Config::app('encoding');
		}

		return '<form' . Html::attributes($attributes) . '>';
	}

	protected static function method($method) {
		return ($method !== 'GET') ? 'POST' : $method;
	}

	protected static function action($action) {
		return Uri::to($action);
	}

	public static function open_multipart($action, $attributes = array()) {
		$attributes['enctype'] = 'multipart/form-data';

		return static::open($action, $attributes);
	}

	public static function close() {
		return '</form>';
	}

	public static function input($type, $name, $value = '', $attributes = array()) {
		$attributes['type'] = $type;

		$attributes['name'] = $name;

		if($value) $attributes['value'] = $value;

		return Html::element('input', '', $attributes);
	}

	public static function text($name, $value = '', $attributes = array()) {
		return static::input('text', $name, $value, $attributes);
	}

	public static function password($name, $attributes = array()) {
		return static::input('password', $name, '', $attributes);
	}

	public static function hidden($name, $value = '', $attributes = array()) {
		return static::input('hidden', $name, $value, $attributes);
	}

	public static function search($name, $value = '', $attributes = array()) {
		return static::input('search', $name, $value, $attributes);
	}

	public static function email($name, $value = '', $attributes = array()) {
		return static::input('email', $name, $value, $attributes);
	}

	public static function telephone($name, $value = '', $attributes = array()) {
		return static::input('tel', $name, $value, $attributes);
	}

	public static function url($name, $value = '', $attributes = array()) {
		return static::input('url', $name, $value, $attributes);
	}

	public static function number($name, $value = '', $attributes = array()) {
		return static::input('number', $name, $value, $attributes);
	}

	public static function date($name, $value = '', $attributes = array()) {
		return static::input('date', $name, $value, $attributes);
	}

	public static function file($name, $attributes = array()) {
		return static::input('file', $name, '', $attributes);
	}

	public static function textarea($name, $value = '', $attributes = array()) {
		$attributes['name'] = $name;

		if ( ! isset($attributes['rows'])) $attributes['rows'] = 10;

		if ( ! isset($attributes['cols'])) $attributes['cols'] = 50;

		return Html::element('textarea', $value, $attributes);
	}

	public static function select($name, $options = array(), $selected = null, $attributes = array()) {
		$attributes['name'] = $name;

		$html = array();

		foreach($options as $value => $display) {
			if(is_array($display)) {
				$html[] = static::optgroup($display, $value, $selected);
			}
			else {
				$html[] = static::option($value, $display, $selected);
			}
		}

		return Html::element('select', implode('', $html), $attributes);
	}

	protected static function optgroup($options, $label, $selected) {
		$html = array();

		foreach($options as $value => $display) {
			$html[] = static::option($value, $display, $selected);
		}

		return Html::element('optgroup', implode('', $html), array('label' => Html::entities($label)));
	}

	protected static function option($value, $display, $selected) {
		$attributes = array('value' => Html::entities($value));

		if( ! is_null($selected)) {
			if((is_array($selected) and in_array($value, $selected)) or ($value == $selected)) {
				$attributes['selected'] = 'selected';
			}
		}

		return Html::element('option', Html::entities($display), $attributes);
	}

	public static function checkbox($name, $value = 1, $checked = false, $attributes = array()) {
		return static::checkable('checkbox', $name, $value, $checked, $attributes);
	}

	public static function radio($name, $value = null, $checked = false, $attributes = array()) {
		if (is_null($value)) $value = $name;

		return static::checkable('radio', $name, $value, $checked, $attributes);
	}

	protected static function checkable($type, $name, $value, $checked, $attributes) {
		if($checked) $attributes['checked'] = 'checked';

		return static::input($type, $name, $value, $attributes);
	}

	public static function submit($value = null, $attributes = array()) {
		return static::input('submit', null, $value, $attributes);
	}

	public static function reset($value = null, $attributes = array()) {
		return static::input('reset', null, $value, $attributes);
	}

	public static function image($url, $name = null, $attributes = array()) {
		$attributes['src'] = URL::to_asset($url);

		return static::input('image', $name, null, $attributes);
	}

	public static function button($value = null, $attributes = array()) {
		if( ! isset($attributes['type'])) $attributes['type'] = 'button';

		return Html::element('button', Html::entities($value), $attributes);
	}

}