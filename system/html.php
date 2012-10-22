<?php namespace System;

class Html {

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

	public static function anchor($uri, $title = '', $attributes = array()) {
		if(strpos('#', $uri) !== 0) {
			$uri = Uri::make($uri);
		}

		if($title == '') {
			$title = $uri;
		}

		$attributes['href'] = $uri;

		return '<a' . static::attributes($attributes) . '>' . $title . '</a>';
	}

	public static function asset($uri) {
		return str_finish(Config::get('application.url'), '/') . $uri;
	}

	public static function linkify($str) {
		// http://daringfireball.net/2010/07/improved_regex_for_matching_urls
		$pattern = '#(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))#i';

		return preg_replace_callback($pattern, function($matches) {
			return '<a href="' . (strpos($matches[0], '://') === false ? '//' : '') . $matches[0] . '">' . $matches[0] . '</a>';
		}, $str);
	}

}