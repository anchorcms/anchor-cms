<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for categories
*/

function has_categories() {
	if(($itm = IoC::resolve('article')) === false) {
		return false;
	}
		
	if(($items = IoC::resolve('categories')) === false) {
		$items = categories::list_all(array('visible' => '1', 'post' => $itm->id));
		IoC::instance('categories', $items, true);
	}
	
	return $items->length() > 0;
}

function total_categories() {
	if(has_categories() === false) {
		return 0;
	}
	
	$items = IoC::resolve('categories');
	return $items->length();
}

// loop categories
function categories() {
	if(has_categories() === false) {
		return false;
	}

	$items = IoC::resolve('categories');

	if($result = $items->valid()) {	
		// register single category
		IoC::instance('category', $items->current(), true);
		
		// move to next
		$items->next();
	}

	return $result;
}

// single categories
function category_id() {
	if($itm = IoC::resolve('category')) {
		return $itm->id;
	}
	
	return '';
}

function category_title() {
	if($itm = IoC::resolve('category')) {
		return $itm->title;
	}
	
	return '';
}

function category_description() {
	if($itm = IoC::resolve('category')) {
		return $itm->description;
	}
	
	return '';
}