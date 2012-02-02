<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	ADMIN Theme functions - happy templating!
*/
function posts($params = array()) {
	static $posts;
	
	if(is_null($posts)) {
		$params['sortby'] = 'created';
		$params['sortmode'] = 'desc';
		$posts = Posts::list_all($params);
	}
	
	return $posts;
}

function article($params = array()) {
	if($itm = IoC::resolve('article')) {
		return $itm;
	}
}

/*
	notifications
*/
function notifications() {
	return Notifications::read();
}

/*
	Post data
*/
function post_user() {
	return Input::post('user');
}

/*
	Main menu
*/
function admin_menu() {
	$pages = array(
		'Posts' => 'admin/posts', 
		'Pages' => 'admin/pages', 
		'Users' => 'admin/users'
	);
	
	return $pages;
}

/*
	Authed users
*/
function user_authed() {
	return Users::authed();
}

/*
	Meta data
*/
function site_name() {
	return Config::get('metadata.sitename');
}

/*
	Url helpers
*/
function theme_url($file = '') {
	return '/system/admin/theme/' . ltrim($file, '/');
}

function current_url() {
	return '/' . Request::uri();
}

/*
	All things search
*/
function search_term() {
	return (Request::uri_segment(1) == 'search' ? Request::uri_segment(2) : '');
}

function search_url() {
	return '/search';
}

function search_results() {
	return IoC::resolve('search');
}
