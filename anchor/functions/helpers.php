<?php

/**
 * Returns the full uri includes the scheme and host
 *
 * @param string
 * @return string
 */
function full_url($url = '') {
	return Uri::full($url);
}

/**
 * Returns a uri
 *
 * @param string
 * @return string
 */
function base_url($url = '') {
	return Uri::to($url);
}

/**
 * Returns a uri relative to the current theme
 *
 * @param string
 * @return string
 */
function theme_url($file = '') {
	$theme_folder = Config::meta('theme');
	$base = 'themes' . '/' . $theme_folder . '/';

	return asset($base . ltrim($file, '/'));
}

/**
 * Include a theme file is the file exists and readable
 *
 * Returns true if the file was included
 *
 * @param string
 * @return bool
 */
function theme_include($file) {
	$theme_folder = Config::meta('theme');
	$base = PATH . 'themes' . DS . $theme_folder . DS;

	if(is_readable($path = $base . ltrim($file, DS) . EXT)) {
		return require $path;
	}
}

/**
 * Returns a uri relative to anchor admin
 *
 * @param string
 * @return string
 */
function asset_url($extra = '') {
	return asset('anchor/views/assets/' . ltrim($extra, '/'));
}

/**
 * Returns the current uri
 *
 * @return string
 */
function current_url() {
	return Uri::current();
}

/**
 * Returns the rss uri
 *
 * @return string
 */
function rss_url() {
	return base_url('feeds/rss');
}

/**
 * Stores a function to be called in other template files
 *
 * Usage:
 *	<?php bind('home_page_slug.function_name', function() {return 'hello';}); ?>
 *
 * @param function Closure
 * @param string
 * @return string
 */
function bind($page, $fn) {
	Events::bind($page, $fn);
}

/**
 * Invokes a stored function
 *
 * Usage:
 *	<?php echo receive('function_name'); ?>
 *
 * @param string
 * @return string
 */
function receive($name = '') {
	return Events::call($name);
}

/**
 * Returns a CSS class of page types and current uri
 *
 * @return string
 */
function body_class() {
	$classes = array();

	//  Get the URL slug
	foreach(explode('/', Uri::current()) as $segment) {
		$classes[] = filter_var($segment, FILTER_SANITIZE_SPECIAL_CHARS);
	}

	//  Is it a posts page?
	if(is_postspage()) {
		$classes[] = 'posts';
	}

	//  Is it the homepage?
	if(is_homepage()) {
		$classes[] = 'home';
	}

	return implode(' ', array_unique($classes));
}

/**
 * Returns true if the current page ID match the home page ID
 *
 * @return bool
 */
function is_homepage() {
	return Registry::prop('page', 'id') == Config::meta('home_page');
}

/**
 * Returns true if the current page ID match the posts listing page ID
 *
 * @return bool
 */
function is_postspage() {
	return Registry::prop('page', 'id') == Config::meta('posts_page');
}

/**
 * Returns true if a article object has been set in the Registry
 *
 * @return bool
 */
function is_article() {
	return Registry::get('article') !== null;
}

/**
 * Returns true if a post_category object has been set in the Registry
 *
 * @return bool
 */
function is_category() {
	return Registry::get('post_category') !== null;
}

/**
 * Returns true if a page object has been set in the Registry
 *
 * @return bool
 */
function is_page() {
	return Registry::get('page') !== null;
}