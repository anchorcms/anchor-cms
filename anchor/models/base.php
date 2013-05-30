<?php

class Base extends Record {

	public static function begin() {
		return Query::table(static::$table)->apply(get_called_class());
	}

	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			$object = strtolower(get_called_class());

			if(isset(Plugin::$hooks[$object][$key])) {
				return call_user_func(Plugin::$hooks[$object][$key], $this->data[$key]);
			}

			return $this->data[$key];
		}
	}

}