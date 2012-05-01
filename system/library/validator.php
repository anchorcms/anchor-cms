<?php

class Validator {

	public static function validate_numeric($value) {
		return is_numeric($value);
	}

	public static function validate_integer($value) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	public static function validate_url($value) {
		return filter_var($value, FILTER_VALIDATE_URL) !== false;
	}

	public static function validate_email($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	public static function validate_alpha($value) {
		return preg_match('/^([a-z])+$/i', $value);
	}

	public static function validate_alpha_num($value) {
		return preg_match('/^([a-z0-9])+$/i', $value);
	}

	public static function validate_alpha_dash($value) {
		return preg_match('/^([-a-z0-9_-])+$/i', $value);	
	}

	public static function validate_regex($value, $pattern) {
		return preg_match('/^' . $pattern . '$/i', $value) ? true : false;	
	}

}