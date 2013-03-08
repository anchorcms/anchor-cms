<?php

/*
 * Set your applications current timezone
 */
date_default_timezone_set(Config::app('timezone', 'UTC'));

/*
 * Define the application error reporting level based on your environment
 */
switch(constant('ENV')) {
	case 'dev':
		ini_set('display_errors', false);
		error_reporting(-1);
		break;

	default:
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory(array(
	APP . 'models',
	APP . 'libraries'
));

/**
 * Helpers
 */
function __($line, $default = 'No language replacement') {
	$args = array_slice(func_get_args(), 2);

	return Language::line($line, $default, $args);
}

function is_admin() {
	return strpos(Uri::current(), 'admin') === 0;
}

function is_installed() {
	return Config::get('db') !== null;
}

function slug($str, $separator = '-') {
	$str = normalize($str);

	// replace non letter or digits by separator
	$str = preg_replace('#[^\\pL\d]+#u', $separator, $str);

	return trim(strtolower($str), $separator);
}

function parse($str) {
	// process tags
	$pattern = '/[\{\{]{1}([a-z]+)[\}\}]{1}/i';

	if(preg_match_all($pattern, $str, $matches)) {
		list($search, $replace) = $matches;

		foreach($replace as $index => $key) {
			$replace[$index] = Config::meta($key);
		}

		$str = str_replace($search, $replace, $str);
	}

	$str = html_entity_decode($str, ENT_NOQUOTES, System\Config::app('encoding'));

	$md = new Markdown;

	return $md->transform($str);
}

function readable_size($size) {
	$unit = array('b','kb','mb','gb','tb','pb');

	return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function format_bug_report($bug) {
	$message =
		'Bug Report' . "\r\n" .
		'----------' . "\r\n" .
		$bug['message'] . "\r\n" .

		'Origin' . "\r\n" .
		'----------' . "\r\n" .
		$bug['file'] . ' on line ' . $bug['line'] . "\r\n" .

		'Trace' . "\r\n" .
		'----------' . "\r\n" .
		str_replace(', ', "\r\n", $bug['trace']);

	return $message;
}

/**
 * Bug report?
 */
if($bug = Arr::get($_POST, '_bug_report')) {
	$headers = implode("\r\n", array('MIME-Version: 1.0', 'Content-type: text/plain; charset=utf-8'));
	mail('bug@kieronwilson.co.uk', 'Bug Report', format_bug_report($bug), $headers);
	exit;
}

/**
 * Anchor setup
 */
Anchor::setup();