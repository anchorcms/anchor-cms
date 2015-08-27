<?php

class Notify {

	public static $types = array('error', 'notice', 'success', 'warning');
	public static $wrap = '<div class="notifications">%s</div>';
	public static $mwrap = '<p class="%s">%s</p>';

	public static function add($type, $messages) {
		if( ! in_array($type, static::$types)) {
			return;
		}

		if( ! is_array($messages)) {
			$messages = array($messages);
		}

		$key = sprintf('messages.%s', $type);

		$messages = array_merge(Session::get($key, array()), $messages);

		Session::put($key, $messages);
	}

	public static function read() {
		$types = Session::get('messages');

		// no messages no problem
		if(is_null($types)) return '';

		$html = '';

		foreach($types as $type => $messages) {
			foreach($messages as $message) {
				$html .= sprintf(static::$mwrap, $type, implode('<br>', (array) $message));
			}
		}

		Session::erase('messages');

		return sprintf(static::$wrap, $html);
	}

	public static function __callStatic($method, $paramaters = array()) {
		static::add($method, array_shift($paramaters));
	}

}
