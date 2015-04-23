<?php

/**
 * Theme functions for posts
 */

/**
 * Are there really some posts here?
 * @return boolean
 */
function has_posts() {
	return Registry::get('total_posts', 0) > 0;
}

function posts() {
	$posts = Registry::get('posts');

	if($result = $posts->valid()) {
		// register single post
		Registry::set('article', $posts->current());

		// move to next
		$posts->next();
	}
	// back to the start
	else $posts->rewind();

	return $result;
}

/**
 * Grab the url of the page that displays all the posts after this page
 * @return String
 */
function posts_next($text = 'Next &rarr;', $default = '') {
	$total = Registry::get('total_posts');
	$offset = Registry::get('page_offset');
	$per_page = Config::meta('posts_per_page');
	$page = Registry::get('page');
	$url = base_url($page->slug . '/');

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/');
	}

	$pagination = new Paginator(array(), $total, $offset, $per_page, $url);

	return $pagination->prev_link($text, $default);
}

/**
 * Grab the url of the page that displays all the posts before this page
 * @return String
 */
function posts_prev($text = '&larr; Previous', $default = '') {
	$total = Registry::get('total_posts');
	$offset = Registry::get('page_offset');
	$per_page = Config::meta('posts_per_page');
	$page = Registry::get('page');
	$url = base_url($page->slug . '/');

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/');
	}

	$pagination = new Paginator(array(), $total, $offset, $per_page, $url);

	return $pagination->next_link($text, $default);
}

/**
 * Grab the amount of posts that are here
 * @return int
 */
function total_posts() {
	return Registry::get('total_posts');
}

/**
 * Are there even enough posts to allow for pagination?
 * @return boolean
 */
function has_pagination() {
	return Registry::get('total_posts') > Config::meta('posts_per_page');
}

/**
 * Grab the amount of posts allowed per page
 * @return int
 */
function posts_per_page() {
	return min(Registry::get('total_posts'), Config::meta('posts_per_page'));
}