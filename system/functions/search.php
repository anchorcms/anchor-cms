<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for search
*/
function has_search_results() {
	if($items = IoC::resolve('search')) {
		return $items->length() > 0;
	}
	
	return false;
}

function total_search_results() {
	if($total = IoC::resolve('total_search')) {
		return $total;
	}
	
	return 0;
}

function search_results() {
	$posts = IoC::resolve('search');

	if($result = $posts->valid()) {	
		// register single post
		IoC::instance('article', $posts->current(), true);
		
		// move to next
		$posts->next();
	}

	return $result;
}

function search_term() {
	return (Request::uri_segment(1) == 'search' ? Request::uri_segment(2) : '');
}

function search_next($text = 'Next', $default = '') {
	$per_page = Config::get('metadata.posts_per_page');
	$offset = Input::get('offset', 0);
	$total = IoC::resolve('total_search');

	$pages = floor($total / $per_page);
	$page = $offset / $per_page;

	if($page < $pages) {
		return '<a href="' . current_url() . '?offset=' . ($offset + $per_page) . '">' . $text . '</a>';
	}

	return $default;
}

function search_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('metadata.posts_per_page');
	$offset = Input::get('offset', 0);
	$total = IoC::resolve('total_search');

	$pages = ceil($total / $per_page);
	$page = $offset / $per_page;

	if($offset > 0) {
		return '<a href="' . current_url() . '?offset=' . ($offset - $per_page) . '">' . $text . '</a>';
	}

	return $default;
}