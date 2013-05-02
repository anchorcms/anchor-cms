<?php

/**
	Theme functions for search
*/
function has_search_results() {
	return Registry::get('total_posts', 0) > 0;
}

function total_search_results() {
	return Registry::get('total_posts', 0);
}

function search_results() {
	$posts = Registry::get('search_results');

	if($result = $posts->valid()) {
		// register single post
		Registry::set('article', $posts->current());

		// move to next
		$posts->next();
	}

	return $result;
}

function search_term() {
	return Registry::get('search_term');
}

function has_search_pagination() {
	return Registry::get('total_posts') > Config::meta('posts_per_page');
}

function search_next($text = 'Next', $default = '') {
	$per_page = Config::meta('posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = floor($total / $per_page);

	$search_page = Registry::get('page');
	$next = $page + 1;
	$term = Registry::get('search_term');
	Session::put(slug($term), $term);

	$url = base_url($search_page->slug . '/' . $term . '/' . $next);

	if(($page - 1) < $pages) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function search_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = ceil($total / $per_page);

	$search_page = Registry::get('page');
	$prev = $page - 1;
	$term = Registry::get('search_term');
	Session::put(slug($term), $term);

	$url = base_url($search_page->slug . '/' . $term . '/' . $prev);

	if($offset > 0) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function search_url() {
	return base_url('search');
}

function search_form_input($extra = '') {
	return '<input name="term" type="text" ' . $extra . ' value="' . search_term() . '">';
}