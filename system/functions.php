<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Theme functions - happy templating!
*/
function posts($params = array()) {
	static $posts;
	
	if(is_null($posts)) {
		$params['status'] = 'published';
		$params['sortby'] = 'created';
		$params['sortmode'] = 'desc';
		$posts = Posts::list_all($params);
	}
	
	return $posts;
}

/*
	Article
*/
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

function article_html() {
	if($itm = IoC::resolve('article')) {
		return $itm->html;
	}
	
	return '';
}

function article_title() {
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}
	
	return '';
}

function article_id() {
	if($itm = IoC::resolve('article')) {
		return $itm->id;
	}
	
	return '';
}

function customised() {
	if($itm = IoC::resolve('article')) {
		return strlen($itm->css)  > 0 or strlen($itm->js)> 0;
	}
	
	return false;
}

/*
	Page
*/
function pages($params = array()) {
	static $pages;
	
	if(is_null($pages)) {
		$params['status'] = 'published';
		$pages = Pages::list_all($params);
	}
	
	return $pages;
}

function page_title($default = '') {
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}
	if($itm = IoC::resolve('page')) {
		return $itm->title;
	}

	return $default;
}

function page_id() {
	if($itm = IoC::resolve('page')) {
		return $itm->id;
	}
	
	return '';
}

function page_content() {
	if($itm = IoC::resolve('page')) {
		return $itm->content;
	}
	
	return '';
}

/*
	Typekit
*/
function fonts() {
	return Typekit::fonts();
}

/*
	Meta data
*/
function site_name() {
	return Config::get('metadata.sitename');
}

function site_description() {
	return Config::get('metadata.description');
}

/*
	Url helpers
*/
function theme_url($file = '') {
	return '/themes/' . Config::get('theme') . '/' . ltrim($file, '/');
}

function current_url() {
	return Request::uri();
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

/*
	Users
*/
function user_authed() {
	return Users::authed() !== false;
}

/*
	Misc helpers
*/
function numeral($number) {
	$test = abs($number) % 10;
	$ext = ((abs($number) % 100 < 21 and abs($number) % 100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
	return $number . $ext; 
}

function count_words($str) {
	return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}
