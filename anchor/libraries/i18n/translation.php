<?php namespace i18n;

class Translation {

	private $catalogue;

	private static $instance;

	public function __construct($catalogue) {
		$this->catalogue = $catalogue;
	}

	public function translate($message, $params = array()) {
		$text = isset($this->catalogue[$message]) ? $this->catalogue[$message] : $message;

		if(count($params)) {
			return call_user_func_array('sprintf', array_merge(array($text), $params));
		}

		return $text;
	}

	public static function __($message, $params = array()) {
		if(is_null(static::$instance)) {
			$locale = Locale::getDefault();
			$collection = Language::catalogue($locale);

			static::$instance = new static($collection);
		}

		return static::$instance->translate($message, $params);
	}

}