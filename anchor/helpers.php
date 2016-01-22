<?php

function __($line) {
	$args = array_slice(func_get_args(), 1);

	return Language::line($line, null, $args);
}

function is_admin() {
	return strpos(Uri::current(), 'admin') === 0;
}

function is_installed() {
	return Config::get('db') !== null or Config::get('database') !== null;
}

function slug($string, $separator = '-') {
	$accents_regex = '~&([a-zA-Z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
	$special_cases = array(
		'&' => 'and'
	);
	$string = mb_strtolower(trim($string) , 'UTF-8');
	$string = str_replace(array_keys($special_cases) , array_values($special_cases) , $string);
	$string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
	$string = preg_replace("/[^a-zA-Z0-9]/u", "$separator", $string);
	$string = preg_replace("/[$separator]+/u", "$separator", $string);
	$string = trim($string, '-');
	return $string;
}

function parse($str, $markdown = true) {
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

	//  Parse Markdown as well?
	if($markdown === true) {
		$md = new Parsedown;
		$str = $md->text($str);
	}

	return $str;
}

function readable_size($size) {
	$unit = array('b','kb','mb','gb','tb','pb');

	return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}
