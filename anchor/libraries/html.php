<?php

class Html {

	public static $encoding;

	public static function encoding() {
		if(is_null(static::$encoding)) static::$encoding = Config::app('encoding');

		return static::$encoding;
	}

	public static function attributes($attributes) {
		if(empty($attributes)) return '';

		if(is_string($attributes)) return ' ' . $attributes;

		foreach($attributes as $key => $val) {
			$pairs[] = $key . '="' . $val . '"';
		}

		return ' ' . implode(' ', $pairs);
	}

	public static function entities($value) {
		return htmlentities($value, ENT_QUOTES, static::encoding(), false);
	}

	public static function decode($value) {
		return html_entity_decode($value, ENT_QUOTES, static::encoding());
	}

	public static function specialchars($value) {
		return htmlspecialchars($value, ENT_QUOTES, static::encoding(), false);
	}

	public static function element($name, $content = '', $attributes = null) {
		$short = array('img', 'input', 'br', 'hr', 'frame', 'area', 'base', 'basefont',
			'col', 'isindex', 'link', 'meta', 'param');

		if(in_array($name, $short)) {
			if($content) $attributes['value'] = $content;

			return '<' . $name . static::attributes($attributes) . '>';
		}

		return '<' . $name . static::attributes($attributes) . '>' . $content . '</' . $name . '>';
	}

	public static function link($uri, $title = '', $attributes = array()) {
		if(strpos('#', $uri) !== 0) $uri = Uri::to($uri);

		if($title == '') $title = $uri;

		$attributes['href'] = $uri;

		return static::element('a', $title, $attributes);
	}

}