<?php

/**
	Theme functions for categories
*/

function total_categories() {
	if( ! $categories = Registry::get('categories')) {
		$categories = Category::all();

		$categories = new Items($categories);

		Registry::set('categories', $categories);
	}

	return $categories->length();
}

// loop categories
function categories() {
	if( ! total_categories()) return false;

	$items = Registry::get('categories');

	if($result = $items->valid()) {
		// register single category
		Registry::set('category', $items->current());
		
		// move to next
		$items->next();
	}

	return $result;
}

// single categories
function category_id() {
	if($itm = Registry::get('category')) {
		return $itm->id;
	}
	
	return '';
}

function category_title() {
	if($itm = Registry::get('category')) {
		return $itm->title;
	}
	
	return '';
}

function category_description() {
	if($itm = Registry::get('category')) {
		return $itm->description;
	}
	
	return '';
}

function category_url() {
	if($itm = Registry::get('category')) {
		return base_url('category/' . $itm->slug);
	}
	
	return '';
}