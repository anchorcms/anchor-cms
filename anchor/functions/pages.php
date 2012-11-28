<?php

/**
	Theme functions for pages
*/
function page_id() {
	return Registry::prop('page', 'id');
}

function page_url() {
	return Registry::prop('page', 'url');
}

function page_slug() {
	return Registry::prop('page', 'slug');
}

function page_name() {
	return Registry::prop('page', 'name');
}

function page_title($default = '') {
	if($title = Registry::prop('page', 'title')) {
		return $title;
	}

	if($title = Registry::prop('article', 'title')) {
		return $title;
	}

	return $default;
}

function page_content() {
	$md = new Markdown;

	return $md->transform(Registry::prop('page', 'content'));
}

function page_active() {
	return Registry::prop('page', 'active');
}

function page_status() {
	return Registry::prop('page', 'status');
}