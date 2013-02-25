<?php

/*
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
		$page = $pages->current();

		$page->active = (Uri::current() == $page->uri($relative = true));

		Registry::set('menu_item', $page);

		$pages->next();
	}

	// back to the start
	if( ! $result) $pages->rewind();

	return $result;
}

/*
	Object props
*/

function menu_id() {
	return Registry::prop('menu_item', 'id');
}

function menu_url() {
	if($page = Registry::get('menu_item')) {
		return $page->uri();
	}
}

function menu_name() {
	return Registry::prop('menu_item', 'name');
}

function menu_title() {
	return Registry::prop('menu_item', 'title');
}

function menu_active() {
	return Registry::prop('menu_item', 'active');
}
