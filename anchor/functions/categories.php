<?php

/**
 * Theme functions for categories
 */

/**
 * How many categories there are.
 * @return int
 */
function total_categories() {
	if( ! $categories = Registry::get('categories')) {
		$categories = Category::get();

		$categories = new Items($categories);

		Registry::set('categories', $categories);
	}

	return $categories->length();
}

/**
 * Loop through all the categories.
 * @return boolean - Do we have any(more)?
 */
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

/**
 * Grab the id of the current category
 * @return int
 */
function category_id() {
	return Registry::prop('category', 'id');
}

/**
 * Grab the title of the current category
 * @return String
 */
function category_title() {
	return Registry::prop('category', 'title');
}

/**
 * Grab the slug of the current category
 * @return String
 */
function category_slug() {
	return Registry::prop('category', 'slug');
}

/**
 * Grab the description of the current category
 * @return String
 */
function category_description() {
	return Registry::prop('category', 'description');
}

/**
 * Grab the url of the current category
 * @return String
 */
function category_url() {
	return base_url('category/' . category_slug());
}

function category_count() {
	return Query::table(Base::table('posts'))
		->where('category', '=', category_id())
		->where('status', '=', 'published')->count();
}