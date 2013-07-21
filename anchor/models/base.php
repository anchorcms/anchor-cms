<?php

class Base extends Record {

	public static function begin() {
		return Query::table(static::$table)->apply(get_called_class());
	}

	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			$index = strtolower(get_called_class()) . '.' . $key;
			$value = $this->data[$key];

			if(isset(Plugin::$callbacks[$index])) {
				foreach(Plugin::$callbacks[$index] as $callback) {
					$value = call_user_func($callback, $value);
				}
			}

			return $value;
		}
	}

}