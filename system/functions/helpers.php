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
function bind($page, $fn, $area = 'main') {
	Events::bind($page, $fn, $area);
}

function recieve($area = '') {
	return Events::call($area);
}