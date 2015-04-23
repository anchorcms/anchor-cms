<?php

/**
 *	Theme functions for pages
 */
 
 /**
 * Grab the page id
 * @return int
 */
function page_id() {
	return Registry::prop('page', 'id');
}

/**
 * Grab the page url
 * @return String
 */
function page_url() {
	if($page = Registry::get('page')) {
		return $page->uri();
	}
}

/**
 * Grab the page slug
 * @return String
 */
function page_slug() {
	return Registry::prop('page', 'slug');
}

/**
 * Grab the page name
 * @return String
 */
function page_name() {
	return Registry::prop('page', 'name');
}

/**
 * Grab the page title
 * @return String
 */
function page_title($default = '') {
	if($title = Registry::prop('article', 'title')) {
		return $title;
	}

	if($title = Registry::prop('page', 'title')) {
		return $title;
	}

	return $default;
}

/**
 * Grab the page content
 * @return String
 */
function page_content() {
	return parse(Registry::prop('page', 'content'));
}

/**
 * Grab the page status (published, draft, etc.)
 * @return String
 */
function page_status() {
	return Registry::prop('page', 'status');
}

/**
 * Grab the page custom field
 * @return String
 */
function page_custom_field($key, $default = '') {
	$id = Registry::prop('page', 'id');

	if($extend = Extend::field('page', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}