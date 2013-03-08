<?php

class Json  {

	public static function encode($obj) {
		return json_encode($obj);
	}

	public static function decode($json, $assoc = false) {
		$result = json_decode($json, $assoc);

		switch(json_last_error()) {
			case JSON_ERROR_DEPTH:
				$error = 'Maximum stack depth exceeded';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Underflow or the modes mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Unexpected control character found';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON';
				break;
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
			case JSON_ERROR_NONE:
			default:
				$error = '';
		}

		if($error) {
			throw new ErrorException('Json Error: ' . $error);
		}

		return $result;
	}

}