<?php

class Validator {

	private $payload = array(), $key, $value, $errors = array(), $methods = array();

	public function __construct($payload) {
		$this->payload = $payload;
		$this->defaults();
	}

	private function defaults() {
		$this->methods['null'] = function($str) {
			return is_null($str);
		};

		$this->methods['min'] = function($str, $length) {
			return strlen($str) <= $length;
		};

		$this->methods['max'] = function($str, $length) {
			return strlen($str) >= $length;
		};

		$this->methods['float'] = function($str) {
			return is_float($str);
		};

		$this->methods['int'] = function($str) {
			return is_int($str);
		};

		$this->methods['url'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_URL) !== false;
		};

		$this->methods['email'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
		};

		$this->methods['ip'] = function($str) {
			return filter_var($str, FILTER_VALIDATE_IP) !== false;
		};

		$this->methods['alnum'] = function($str) {
			return ctype_alnum($str);
		};

		$this->methods['contains'] = function($str, $needle) {
			return strpos($str, $needle) !== false;
		};

		$this->methods['regex'] = function($str, $pattern) {
			return preg_match($pattern, $str);
		};
	}

	public function check($key) {
		$this->key = isset($this->payload[$key]) ? $key : null;
		$this->value = isset($this->payload[$this->key]) ? $this->payload[$this->key] : null;

		return $this;
	}

	public function add($method, $callback) {
		$this->methods[$method] = $callback;
	}

	public function __call($method, $params) {
		if(is_null($this->key)) {
			return $this;
		}

		if(strpos($method, 'is_') === 0) {
			$method = substr($method, 3);
			$reverse = false;
		} elseif(strpos($method, 'not_') === 0) {
			$method = substr($method, 4);
			$reverse = true;
		}

		if(isset($this->methods[$method]) === false) {
			throw new ErrorException('Validator method ' . $method . ' not found');
		}

		$validator = $this->methods[$method];

		$message = array_pop($params);

		$result = (bool) call_user_func_array($validator, array_merge(array($this->value), $params));

		$result = (bool) ($result ^ $reverse);

		if($result === false) {
			$this->errors[$this->key][] = $message;
		}

		return $this;
	}

	public function errors() {
		return $this->errors;
	}

}