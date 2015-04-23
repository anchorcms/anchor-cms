<?php

/**
 * 	Theme functions for search
 */
 
/**
 * Did the search even return anything useful?
 * @return boolean
 */
function has_search_results() {
	return Registry::get('total_posts', 0) > 0;
}

/**
 * How many search results came up?
 * @return int
 */
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

/**
 * Grab the search term used
 * @return String
 */
function search_term() {
	return Registry::get('search_term');
}

/**
 * Are there enough results to allow for pagination?
 * @return boolean
 */
function has_search_pagination() {
	return Registry::get('total_posts') > Config::meta('posts_per_page');
}

/**
 * Grab the link that gets us to the next page of search results
 * @return String
 */
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

/**
 * Grab the link that gets us to the previous page of search results
 * @return String
 */
function search_prev($text = 'Previous', $default = '') {
	$per_page = Config::meta('posts_per_page');
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

/**
 * Grab the link that is used to send the search data
 * @return String
 */
function search_url() {
	return base_url('search');
}

/**
 * Grab the HTML code for creating an input that is used as the input
 * @return String
 */
function search_form_input($extra = '') {
	return '<input name="term" type="text" ' . $extra . ' value="' . search_term() . '">';
}

/**
 * Grab the HTML code for creating the search button
 * @return String
 */
function search_form_submit($button_text = 'Search', $extra = '') {
	return '<input type="submit" ' . $extra . ' value="' . $button_text . '">';
}
