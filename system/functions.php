<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions - happy templating!
*/

/*
	Posts
*/
function has_posts() {
	if(($posts = IoC::resolve('posts')) === false) {
		$params['status'] = 'published';
		$params['sortby'] = 'id';
		$params['sortmode'] = 'desc';
		
		$posts = Posts::list_all($params);
		IoC::instance('posts', $posts, true);
	}
	
	return $posts->length() > 0;
}

function posts($params = array()) {
	if(has_posts() === false) {
		return false;
	}
	
	$posts = IoC::resolve('posts');

	if($result = $posts->valid()) {	
		// register single post
		$posts = IoC::resolve('posts');
		IoC::instance('post', $posts->current(), true);
		
		// move to next
		$posts->next();
	}

	return $result;
}

function post_id() {
	if($itm = IoC::resolve('post')) {
		return $itm->id;
	}
	
	return '';
}

function post_title() {
	if($itm = IoC::resolve('post')) {
		return $itm->title;
	}
	
	return '';
}

function post_slug() {
	if($itm = IoC::resolve('post')) {
		return $itm->slug;
	}
	
	return '';
}

function post_url() {
	if($itm = IoC::resolve('post')) {
		$page = IoC::resolve('postspage');
		return '/' . $page->slug . '/' . $itm->slug;
	}
	
	return '';
}

function post_description() {
	if($itm = IoC::resolve('post')) {
		return $itm->description;
	}
	
	return '';
}

function post_html() {
	if($itm = IoC::resolve('post')) {
		return $itm->html;
	}
	
	return '';
}

function post_css() {
	if($itm = IoC::resolve('post')) {
		return $itm->css;
	}
	
	return '';
}

function post_js() {
	if($itm = IoC::resolve('post')) {
		return $itm->js;
	}
	
	return '';
}

function post_date() {
	if($itm = IoC::resolve('post')) {
		return date(Config::get('metadata.date_format'), strtotime($itm->created));
	}
	
	return '';
}

function post_author() {
	if($itm = IoC::resolve('post')) {
		return $itm->author;
	}
	
	return '';
}

/**
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

function article_url() {
	if($itm = IoC::resolve('article')) {
		$page = IoC::resolve('postspage');
		return '/' . $page->slug . '/' . $itm->slug;
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

function article_date() {
	if($itm = IoC::resolve('article')) {
		return date(Config::get('metadata.date_format'), $itm->created);
	}
	
	return '';
}

function article_author() {
	if($itm = IoC::resolve('article')) {
		return $itm->author;
	}
	
	return '';
}

function customised() {
	if($itm = IoC::resolve('article')) {
		return strlen($itm->css)  > 0 or strlen($itm->js)> 0;
	}
	
	return false;
}

/**
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

/**
	Meta data
*/
function site_name() {
	return Config::get('metadata.sitename');
}

function site_description() {
	return Config::get('metadata.description');
}

/**
	Url helpers
*/
function theme_url($file = '') {
	return '/themes/' . Config::get('metadata.theme') . '/' . ltrim($file, '/');
}

function current_url() {
	return Request::uri();
}

/**
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

/**
	Users
*/
function user_authed() {
	return Users::authed() !== false;
}

/**
	Misc helpers
*/
function twitter_account() {
	return Config::get('metadata.twitter');
}

function numeral($number) {
	$test = abs($number) % 10;
	$ext = ((abs($number) % 100 < 21 and abs($number) % 100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
	return $number . $ext; 
}

function count_words($str) {
	return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}

function pluralise($amount, $str, $alt = '') {
    return $amount === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function relative_time($date) {
    $elapsed = time() - $date;
    
    if($elapsed <= 1) {
        return 'Just now';
    }
    
    $times = array(
        31104000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    
    foreach($times as $seconds => $title) {
        $rounded = $elapsed / $seconds;
        
        if($rounded > 1) {
            $rounded = round($rounded);
            return $rounded . ' ' . pluralise($rounded, $title) . ' ago';
        }
    }
}

