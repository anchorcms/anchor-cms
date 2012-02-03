<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	ADMIN Theme functions - happy templating!
*/

/*
	Posts
*/
function has_posts() {
	if(($posts = IoC::resolve('posts')) === false) {
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

function post_status() {
	if($itm = IoC::resolve('post')) {
		return $itm->status;
	}

	return '';
}

/*
	Users
*/
function users($params = array()) {
	static $items;
	
	if(is_null($items)) {
		$params['sortby'] = 'id';
		$params['sortmode'] = 'desc';
		$items = Users::list_all($params);
	}
	
	return $items;
}

function user_id() {
	if($itm = IoC::resolve('user')) {
		return $itm->id;
	}
	
	return '';
}

function user_name() {
	if($itm = IoC::resolve('user')) {
		return $itm->username;
	}
	
	return '';
}

function user_real_name() {
	if($itm = IoC::resolve('user')) {
		return $itm->real_name;
	}
	
	return '';
}

function user_bio() {
	if($itm = IoC::resolve('user')) {
		return $itm->bio;
	}
	
	return '';
}

function user_status() {
	if($itm = IoC::resolve('user')) {
		return $itm->status;
	}
	
	return '';
}

function user_role() {
	if($itm = IoC::resolve('user')) {
		return $itm->role;
	}
	
	return '';
}

function user_authed() {
	return Users::authed() !== false;
}

function user_authed_id() {
	if(user_authed()) {
		return Users::authed()->id;
	}
	
	return '';
}

function user_authed_realname() {
	if(user_authed()) {
		return Users::authed()->real_name;
	}
	
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
	Pages
*/
function pages($params = array()) {
	static $posts;
	
	if(is_null($posts)) {
		$params['sortby'] = 'name';
		$params['sortmode'] = 'asc';
		$posts = Pages::list_all($params);
	}
	
	return $posts;
}

function page_id() {
	if($itm = IoC::resolve('page')) {
		return $itm->id;
	}
	
	return '';
}

function page_slug() {
	if($itm = IoC::resolve('page')) {
		return $itm->slug;
	}
	
	return '';
}

function page_name() {
	if($itm = IoC::resolve('page')) {
		return $itm->name;
	}
	
	return '';
}

function page_title() {
	if($itm = IoC::resolve('page')) {
		return $itm->title;
	}
	
	return '';
}

function page_content() {
	if($itm = IoC::resolve('page')) {
		return $itm->content;
	}
	
	return '';
}

function page_status() {
	if($itm = IoC::resolve('page')) {
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
		'Users' => 'admin/users',
		'Logout' => 'admin/logout'
	);
	
	return $pages;
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
	Pagination
*/
function pagination() {
	return '';
}


