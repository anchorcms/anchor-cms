<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for menus
*/
function has_menu_items() {
	if(($total = IoC::resolve('total_menu_items')) === false) {
		$total = Pages::count(array('status' => 'published'));
		IoC::instance('total_menu_items', $total, true);
	}
	return $total;
}

function menu_items($params = array()) {
	if(!has_menu_items()) {
		return false;
	}

	if(($pages = IoC::resolve('menu')) === false) {
		$params['status'] = 'published';
		$pages = Pages::list_all($params);
		IoC::instance('menu', $pages, true);
	}

	if($result = $pages->valid()) {	
		// register single post
		IoC::instance('menu_item', $pages->current(), true);
		
		// move to next
		$pages->next();
	}

	return $result;
}

function menu_id() {
	if($itm = IoC::resolve('menu_item')) {
		return $itm->id;
	}
	
	return '';
}

function menu_url() {
	if($itm = IoC::resolve('menu_item')) {
		return $itm->url;
	}
	
	return '';
}

function menu_name() {
	if($itm = IoC::resolve('menu_item')) {
		return $itm->name;
	}
	
	return '';
}

function menu_title() {
	if($itm = IoC::resolve('menu_item')) {
		return $itm->title;
	}

	return $default;
}

function menu_active() {
	if($itm = IoC::resolve('menu_item')) {
		return $itm->active;
	}
	
	return '';
}
