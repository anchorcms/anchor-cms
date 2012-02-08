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
	if($items = IoC::resolve('search')) {
		return $items->length();
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
