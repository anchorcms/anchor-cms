<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme helpers functions
*/


// Url helpers
function base_url($url = '') {
    return Url::make($url);
}

function theme_url($file = '') {
	return Config::get('application.base_url') . 'themes/' . Config::get('metadata.theme') . '/' . ltrim($file, '/');
}

function current_url() {
	return Url::make(Request::uri());
}

function admin_url($url = '') {
    return Url::make(Config::get('application.admin_folder') . '/' . ltrim($url, '/'));
}

function search_url() {
	return Url::make('search');
}

function rss_url() {
    return Url::make('rss');
}

//  Custom function helpers
function bind($page, $fn) {
	Events::bind($page, $fn);
}

function recieve($name = '') {
	return Events::call($name);
}

// page type helpers
function is_homepage() {
	if($itm = IoC::resolve('page')) {
		return $itm->id == Config::get('metadata.home_page');
	}

	return false;
}

function is_postspage() {
	if($itm = IoC::resolve('page')) {
		return $itm->id == Config::get('metadata.posts_page');
	}

	return false;
}

function is_debug() {
	return Config::get('debug', false);
}

// benchmarking
function execution_time() {
	$miliseconds = microtime(true) - ANCHOR_START;
	return round($miliseconds, 2);
}

// return in mb
function memory_usage() {
	return memory_get_peak_usage(true) / 1024 / 1024;
}