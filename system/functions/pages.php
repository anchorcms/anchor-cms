<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for pages
*/
function page_id() {
	if($itm = IoC::resolve('page')) {
		return $itm->id;
	}
	
	return '';
}

function page_url() {
	if($itm = IoC::resolve('page')) {
		return $itm->url;
	}
	
	return '';
}

function page_name() {
	if($itm = IoC::resolve('page')) {
		return $itm->name;
	}
	
	return '';
}

function page_title($default = '') {
	if($itm = IoC::resolve('page')) {
		return $itm->title;
	}
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}

	return $default;
}

function page_content() {
	if($itm = IoC::resolve('page')) {
		return $itm->content;
	}
	
	return '';
}


function page_active() {
	if($itm = IoC::resolve('page')) {
		return $itm->active;
	}
	
	return '';
}

function page_status() {
	if($itm = IoC::resolve('page')) {
		return $itm->status;
	}
	
	return '';
}