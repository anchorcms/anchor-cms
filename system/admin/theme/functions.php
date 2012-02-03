<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	ADMIN Theme functions - happy templating!
*/
function posts($params = array()) {
	static $posts;
	
	if(is_null($posts)) {
		$params['sortby'] = 'id';
		$params['sortmode'] = 'desc';
		$posts = Posts::list_all($params);
	}
	
	return $posts;
}

function pagination() {
	return '';
}

/*
	Article
*/
function article_id() {
	if($itm = IoC::resolve('article')) {
		return $itm->id;
	}
	
	return '';
}

function article_title() {
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}
	
	return '';
}

function article_slug() {
	if($itm = IoC::resolve('article')) {
		return $itm->slug;
	}
	
	return '';
}

function article_description() {
	if($itm = IoC::resolve('article')) {
		return $itm->description;
	}
	
	return '';
}

function article_html() {
	if($itm = IoC::resolve('article')) {
		return $itm->html;
	}
	
	return '';
}

function article_css() {
	if($itm = IoC::resolve('article')) {
		return $itm->css;
	}
	
	return '';
}

function article_js() {
	if($itm = IoC::resolve('article')) {
		return $itm->js;
	}
	
	return '';
}

function article_created() {
	if($itm = IoC::resolve('article')) {
		return $itm->created;
	}
	
	return '';
}

function article_author() {
	if($itm = IoC::resolve('article')) {
		return $itm->author;
	}
	
	return '';
}

function article_status() {
	if($itm = IoC::resolve('article')) {
		return $itm->status;
	}
	
	return '';
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
		'Metadata' => 'admin/metadata', 
		'Users' => 'admin/users',
		'Logout' => 'admin/logout'
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

