<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for posts
*/
function has_posts() {
	if(($posts = IoC::resolve('posts')) === false) {
		$params = array(
			'status' => 'published', 
			'sortby' => 'id', 
			'sortmode' => 'desc', 
			'limit' => Config::get('metadata.posts_per_page', 10), 
			'offset' => Input::get('offset', 0)
		);
		$posts = Posts::list_all($params);
		IoC::instance('posts', $posts, true);

		$total_posts = Posts::count(array('status' => 'published'));
		IoC::instance('total_posts', $total_posts, true);
	}
	
	return $posts->length() > 0;
}

function posts() {
	if(has_posts() === false) {
		return false;
	}
	
	$posts = IoC::resolve('posts');

	if($result = $posts->valid()) {	
		// register single post
		IoC::instance('article', $posts->current(), true);
		
		// move to next
		$posts->next();
	}

	return $result;
}

function posts_next($text = 'Next', $default = '') {
	$per_page = Config::get('metadata.posts_per_page');
	$offset = Input::get('offset', 0);
	$total = IoC::resolve('total_posts');

	$pages = floor($total / $per_page);
	$page = $offset / $per_page;

	if($page < $pages) {
		return '<a href="' . current_url() . '?offset=' . ($offset + $per_page) . '">' . $text . '</a>';
	}

	return $default;
}

function posts_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('metadata.posts_per_page');
	$offset = Input::get('offset', 0);
	$total = IoC::resolve('total_posts');

	$pages = ceil($total / $per_page);
	$page = $offset / $per_page;

	if($offset > 0) {
		return '<a href="' . current_url() . '?offset=' . ($offset - $per_page) . '">' . $text . '</a>';
	}

	return $default;
}