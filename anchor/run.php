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
		ini_set('display_errors', true);
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

function show_installer() {
	if( ! is_installed()) {
		echo View::create('intro')->yield();

		exit(0);
	}
}

function load_meta() {
	// load database metadata
	foreach(Query::table('meta')->get() as $item) {
		$meta[$item->key] = $item->value;
	}

	Config::set('meta', $meta);
}

function load_theming_functions() {
	if(IS_ADMIN) return;

	$fi = new FilesystemIterator(APP . 'functions', FilesystemIterator::SKIP_DOTS);

	foreach($fi as $file) {
		if($file->isFile() and $file->isReadable() and pathinfo($file->getPathname(), PATHINFO_EXTENSION) == 'php') {
			require $file->getPathname();
		}
	}

	// include theme functions
	if(is_readable($path = PATH . 'themes' . DS . Config::meta('theme') . DS . 'functions.php')) {
		require $path;
	}
}

function load_register() {
	// register home page
	Registry::set('home_page', Page::home());

	// register posts page
	Registry::set('posts_page', Page::posts());

	// register categories
	foreach(Category::get() as $itm) {
		$categories[$itm->id] = $itm;
	}

	Registry::set('all_categories', $categories);
}

function admin_asset($path) {
	return asset('anchor/views/assets/' . $path);
}

function admin_url($path = '') {
	return Uri::to('admin/' . $path);
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

	$md = new Markdown;

	return $md->transform($str);
}


/**
 * Anchor setup
 */
define('IS_ADMIN', is_admin());

// Check installation
show_installer();

// load database metadata
load_meta();

// include theming functions
load_theming_functions();

// load home/post/categories into registery
load_register();

/**
 * Anchor migrations
 */
$current = Config::get('meta.current_migration');
$migrate_to = Config::get('migrations.current');

$migrations = new Migrations($current);

if(is_null($current)) {
	$number = $migrations->up($migrate_to);

	Query::table('meta')->insert(array(
		'key' => 'current_migration',
		'value' => $number
	));
}
else if($current < $migrate_to) {
	$number = $migrations->up($migrate_to);
	Query::table('meta')->where('key', '=', 'current_migration')->update(array('value' => $number));
}