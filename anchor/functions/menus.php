<?php

/**
	Theme functions for menus
*/
function has_menu_items() {
	if( ! $total = Registry::get('total_menu_items')) {
		$total = Page::where('status', '=', 'published')->count();

		Registry::set('total_menu_items', $total);
	}

	return $total;
}

function menu_items() {
	if( ! $pages = Registry::get('menu')) {
		$pages = Page::where('status', '=', 'published')->get();

		$pages = new Items($pages);

		Registry::set('menu', $pages);
	}

	if($result = $pages->valid()) {
		$item = $pages->current();

		$item->active = false;

		$item->url = base_url($item->slug);

		// register single post
		Registry::set('menu_item', $item);
		
		// move to next
		$pages->next();
	}

	return $result;
}

function menu_id() {
	if($itm = Registry::get('menu_item')) {
		return $itm->id;
	}
	
	return '';
}

function menu_url() {
	if($itm = Registry::get('menu_item')) {
		return $itm->url;
	}
	
	return '';
}

function menu_name() {
	if($itm = Registry::get('menu_item')) {
		return $itm->name;
	}
	
	return '';
}

function menu_title() {
	if($itm = Registry::get('menu_item')) {
		return $itm->title;
	}

	return $default;
}

function menu_active() {
	if($itm = Registry::get('menu_item')) {
		return $itm->active;
	}
	
	return '';
}
