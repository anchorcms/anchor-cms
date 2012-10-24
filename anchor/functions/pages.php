<?php

/**
	Theme functions for pages
*/
function page_id() {
	if($itm = Registry::get('page')) {
		return $itm->id;
	}

	return '';
}

function page_url() {
	if($itm = Registry::get('page')) {
		return $itm->url;
	}

	return '';
}

function page_slug() {
	if($itm = Registry::get('page')) {
		return $itm->slug;
	}

	return '';
}

function page_name() {
	if($itm = Registry::get('page')) {
		return $itm->name;
	}

	return '';
}

function page_title($default = '') {
	if($itm = Registry::get('page')) {
		return $itm->title;
	}
	if($itm = Registry::get('article')) {
		return $itm->title;
	}

	return $default;
}

function page_content() {
	if($itm = Registry::get('page')) {
		$md = new Markdown;
		return $md->transform($itm->content);
	}

	return '';
}

function page_active() {
	if($itm = Registry::get('page')) {
		return $itm->active;
	}

	return '';
}

function page_status() {
	if($itm = Registry::get('page')) {
		return $itm->status;
	}

	return '';
}