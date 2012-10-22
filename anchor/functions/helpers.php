<?php

/**
	Theme helpers functions
*/


// Url helpers
function base_url($url = '') {
    return Uri::make($url);
}

function theme_url($file = '') {
	return Html::asset('themes/' . Config::get('meta.theme') . '/' . ltrim($file, '/'));
}

function theme_include($file) {
	if(is_readable($path = PATH . 'themes' . DS . Config::get('meta.theme') . DS . ltrim($file, '/') . '.php')) {
		return require $path;
	}
}

function current_url() {
	return Uri::current();
}

function admin_url($url = '') {
    return base_url('admin/' . ltrim($url, '/'));
}

function search_url() {
	return base_url('search');
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

// create a alias for typo in 0.6 and below so we dont break themes
function recieve() {
	$args = func_get_args();
	return call_user_func_array('receive', $args);
}

// page type helpers
function is_homepage() {
	if($itm = Registry::get('page')) {
		return $itm->id == Config::get('meta.home_page');
	}

	return false;
}

function is_postspage() {
	if($itm = Registry::get('page')) {
		return $itm->id == Config::get('meta.posts_page');
	}

	return false;
}