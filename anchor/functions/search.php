<?php

/**
 * Returns true if there is at least one post
 *
 * @return bool
 */
function has_search_results() {
	return Registry::get('total_posts', 0) > 0;
}

/**
 * Returns the number of posts
 *
 * @return bool
 */
function total_search_results() {
	return Registry::get('total_posts', 0);
}

/**
 * Returns true while there are still items in the array.
 *
 * Updates the current article object in the Registry on each call.
 *
 * @return bool
 */
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
 * Returns the search term
 *
 * @return string
 */
function search_term() {
	return Registry::get('search_term');
}

/**
 * Returns true if there is more posts than the posts per page causing
 * pagination to be generated
 *
 * @return bool
 */
function has_search_pagination() {
	return Registry::get('total_posts') > Config::meta('posts_per_page');
}

/**
 * Returns a html link to the next page of search results
 *
 * @return string
 */
function search_next($text = 'Next', $default = '') {
	$per_page = Config::meta('posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = floor($total / $per_page);

	$posts_page = Registry::get('page');
	$next = $page + 1;

	$url = base_url($posts_page->slug . '/' . $next);

	if(($page - 1) < $pages) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

/**
 * Returns a html link to the previous page of search results
 *
 * @return string
 */
function search_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = ceil($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$prev = $page - 1;

	$url = base_url($posts_page->slug . '/' . $prev);

	if($offset > 0) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

/**
 * Returns the uri to post search queries to
 *
 * @return string
 */
function search_url() {
	return base_url('search');
}

/**
 * Returns the html input for a search
 *
 * @return string
 */
function search_form_input($extra = '') {
	return '<input name="term" type="text" ' . $extra . ' value="' . search_term() . '">';
}