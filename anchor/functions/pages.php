<?php

/**
 *	Theme functions for pages
 */
function page_id() {
	return Registry::prop('page', 'id');
}

function page_url() {
	if($page = Registry::get('page')) {
		return $page->uri();
	}
}

function page_slug() {
	return Registry::prop('page', 'slug');
}

function page_name() {
	return Registry::prop('page', 'name');
}

function page_title($default = '') {
	if($title = Registry::prop('article', 'title')) {
		return $title;
	}

	if($title = Registry::prop('page', 'title')) {
		return $title;
	}

	return $default;
}

function page_content() {
	return parse(Registry::prop('page', 'content'));
}

function page_status() {
	return Registry::prop('page', 'status');
}

function page_custom_field($key, $default = '') {
	$id = Registry::prop('page', 'id');

	if($extend = Extend::field('page', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}