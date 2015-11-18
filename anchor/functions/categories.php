<?php

/**
 * Theme functions for categories
 */

function total_categories() {
	if( ! $categories = Registry::get('categories')) {
		$categories = Category::get();

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

	// back to the start
	if(!$result) $items->rewind();

	return $result;
}

// single categories
function category_id() {
	return Registry::prop('category', 'id');
}

function category_title() {
	return Registry::prop('category', 'title');
}

function category_slug() {
	return Registry::prop('category', 'slug');
}

function category_description() {
	return Registry::prop('category', 'description');
}

function category_url() {
	return base_url('category/' . category_slug());
}

function category_count() {
	return Query::table(Base::table('posts'))
		->where('category', '=', category_id())
		->where('status', '=', 'published')->count();
}

function category_custom_field($key, $default = '') {
	$id = Registry::prop('category', 'id');

	if($extend = Extend::field('category', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}
