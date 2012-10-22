<?php namespace System;

class Form {

	private static function attributes($attributes) {
		if(is_string($attributes)) {
			return ($attributes !== '') ? ' ' . $attributes : '';
		}

		$att = array();

		foreach($attributes as $key => $val) {
			$att[] = $key . '="' . $val . '"';
		}

		return ' ' . implode(' ', $att);
	}

	public static function open($action, $attributes = array()) {
		if(empty($attributes)) {
			$attributes['method'] = 'post';
		}

		if(!isset($attributes['method'])) {
			$attributes['method'] = 'post';
		}

		if(strpos($action, '://') === false) {
			$action = Uri::make($action);
		}

		return '<form action="' . $action . '"' . static::attributes($attributes) . '>';
	}

	public static function open_multipart($action, $attributes = array()) {
		$attributes['enctype'] = 'multipart/form-data';
		return static::open($action, $attributes);
	}

	public static function input($data, $value = '') {
		$defaults = array(
			'type' => 'text',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		$params = is_array($data) ? $data : array();

		return '<input' . static::attributes(array_merge($defaults, $params)) . '>';
	}

	public static function hidden($data, $value = '') {
		if(!is_array($data)) {
			$data = array(
				'name' => $data,
				'value' => $value
			);
		}

		$data['type'] = 'hidden';

		return static::input($data, $value);
	}

	public static function password($data, $value = '') {
		if (!is_array($data)) {
			$data = array('name' => $data);
		}

		$data['type'] = 'password';

		return static::input($data, $value);
	}

	public static function upload($data, $value = '') {
		if (!is_array($data)) {
			$data = array('name' => $data);
		}

		$data['type'] = 'file';

		return static::input($data, $value);
	}

	public static function textarea($data, $value = '') {
		$defaults = array(
			'name' => is_array($data) ? '' : $data,
			'cols' => '90',
			'rows' => '12'
		);

		if(is_array($data) and isset($data['value'])) {
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
		} else {
			$val = $value;
		}

		$params = is_array($data) ? $data : array();

		return '<textarea' . static::attributes(array_merge($defaults, $params)) . '>' . $val . '</textarea>';
	}

	public static function select($name, $options = array(), $selected = array(), $attributes = '') {
		if(!is_array($selected)) {
			$selected = array($selected);
		}

		if(is_array($attributes)) {
			if(isset($attributes['multiple'])) {
				$attributes['multiple'] = 'multiple';
			}

			$attributes = static::attributes($attributes);
		}

		$form = '<select name="' . $name . '"' . $attributes . '>';

		foreach($options as $key => $val) {
			if(is_array($val)) {
				$form .= '<optgroup label="' . $key . '">';

				foreach($val as $grp_key => $grp_val) {
					$sel = in_array($grp_key, $selected) ? ' selected="selected"' : '';

					$form .= '<option value="' . $grp_key . '"' . $sel . '>' . $grp_val . '</option>';
				}

				$form .= '</optgroup>';
			} else {
				$sel = in_array($key, $selected) ? ' selected="selected"' : '';

				$form .= '<option value="' . $key . '"' . $sel . '>' . $val . '</option>';
			}
		}

		$form .= '</select>';

		return $form;
	}

	public static function checkbox($data, $value = '', $checked = false) {
		$defaults = array(
			'type' => 'checkbox',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		if(is_array($data) and array_key_exists('checked', $data)) {
			if($data['checked']) {
				$defaults['checked'] = 'checked';
			}
		}

		if($checked) {
			$defaults['checked'] = 'checked';
		}

		$params = is_array($data) ? $data : array();

		return static::input(array_merge($defaults, $params));
	}

	public static function radio($data, $value = '', $checked = false) {
		if(!is_array($data)) {
			$data = array('name' => $data);
		}

		$data['type'] = 'radio';

		return static::checkbox($data, $value, $checked);
	}

	public static function submit($data, $value = '') {
		$defaults = array(
			'type' => 'submit',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		$params = is_array($data) ? $data : array();

		return static::input(array_merge($defaults, $params));
	}

	public static function button($data, $content = '') {
		$defaults = array(
			// default to submit for IE compat
			'name' => is_array($data) ? 'submit' : $data,
			'type' => 'button'
		);

		if(is_array($data) and isset($data['content'])) {
			$content = $data['content'];
			unset($data['content']); // content is not an attribute
		}

		$params = is_array($data) ? $data : array();
		return '<button' . static::attributes(array_merge($defaults, $params)) . '>' . $content . '</button>';
	}

	public static function close() {
		return "</form>";
	}

}