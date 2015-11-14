<?php

/**
 *	Theme functions for pages
 */
function page_id($page_item = null) {
	if(is_array($page_item)) return $page_item['id'];
	return ($page_item ? $page_item->id : Registry::prop('page', 'id'));
}

function page_url($page_item = null) {
	if(!$page_item) {
		$page_item = Registry::get('page');
	}
	if($page_item) {
		return $page_item->uri();
	}
}

function page_slug($page_item = null) {
	if(is_array($page_item)) return $page_item['slug'];
	return ($page_item ? $page_item->slug : Registry::prop('page', 'slug'));
}

function page_name($page_item = null) {
	if(is_array($page_item)) return $page_item['name'];
	return ($page_item ? $page_item->name : Registry::prop('page', 'name'));
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

function page_content($page_item = null) {
	if(is_array($page_item)) return $page_item['html'];
	return ($page_item ? $page_item->html : Registry::prop('page', 'html'));
}

function page_status($page_item = null) {
	if(is_array($page_item)) return $page_item['status'];
	return ($page_item ? $page_item->status : Registry::prop('page', 'status'));
}

function page_custom_field($key, $default = '') {
	$id = Registry::prop('page', 'id');

	if($extend = Extend::field('page', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}