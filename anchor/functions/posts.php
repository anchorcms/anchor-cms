<?php

/**
 * Theme functions for posts
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

function posts_next($text = 'Next &rarr;', $default = '', $attrs = array()) {
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

	return $pagination->next_link($text, $default, $attrs);
}

function posts_prev($text = '&larr; Previous', $default = '', $attrs = array()) {
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

	return $pagination->prev_link($text, $default, $attrs);
}

function total_posts() {
	return Registry::get('total_posts');
}

function has_pagination() {
	return Registry::get('total_posts') > Config::meta('posts_per_page');
}

function posts_per_page() {
	return min(Registry::get('total_posts'), Config::meta('posts_per_page'));
}