<?php

/**
 * Theme helpers functions
 */
function full_url($url = '') {
	return Uri::full($url);
}

function base_url($url = '') {
    return Uri::to($url);
}

function theme_url($file = '') {
	$theme_folder = Config::meta('theme');
	$base = 'themes' . '/' . $theme_folder . '/';

	return asset($base . ltrim($file, '/'));
}

function theme_include($file) {
	$theme_folder = Config::meta('theme');
	$base = PATH . 'themes' . DS . $theme_folder . DS;

	if(is_readable($path = $base . ltrim($file, DS) . EXT)) {
		return require $path;
	}
}

function asset_url($extra = '') {
	return asset('anchor/views/assets/' . ltrim($extra, '/'));
}

function current_url() {
	return Uri::current();
}

function rss_url() {
    return base_url('feeds/rss');
}

//  Custom function helpers
function bind($page, $fn) {
	Events::bind($page, $fn);
}

function receive($name = '') {
	return Events::call($name);
}

function body_class() {
	$classes = array();

	//  Get the URL slug
	$parts = explode('/', Uri::current());
	$classes[] = count($parts) ? trim(current($parts)) : 'index';

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

// page type helpers
function is_homepage() {
	if(Config::meta('home_page') != Config::meta('posts_page')) {
		return Registry::prop('page', 'id') == Config::meta('home_page');
	} else {
		return current_url() == '' or current_url() == '/';
	}
}

function is_postspage() {
	$posts_page = Registry::get('posts_page');
	$offset = Registry::get('page_offset');

	if(Config::meta('posts_page') != Config::meta('home_page')) {
		return Registry::prop('page', 'id') == Config::meta('posts_page');
	} else {
		return current_url() == $posts_page->slug or current_url() == $posts_page->slug . '/' . $offset;
	}
}

function is_article() {
	return Registry::get('article') !== null;
}

function is_page() {
	return Registry::get('page') !== null;
}
