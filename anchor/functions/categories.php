<?php

/**
 * Returns the total number of categories and sets the Registry with an
 * array of all categories
 *
 * @return string
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
 * Returns true while there are still items in the array.
 *
 * Updates the current category object in the Registry on each call.
 *
 * @return bool
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
 * Returns the category ID
 *
 * @return string
 */
function category_id() {
	return Registry::prop('category', 'id');
}

/**
 * Returns the category title
 *
 * @return string
 */
function category_title() {
	return Registry::prop('category', 'title');
}

/**
 * Returns the category slug
 *
 * @return string
 */
function category_slug() {
	return Registry::prop('category', 'slug');
}

/**
 * Returns the category description
 *
 * @return string
 */
function category_description() {
	return Registry::prop('category', 'description');
}

/**
 * Returns the category url
 *
 * @return string
 */
function category_url() {
	return base_url('category/' . category_slug());
}

/**
 * Returns the number of published posts in the current category
 *
 * @return string
 */
function category_count() {
	return Query::table(Base::table('posts'))
		->where('category', '=', category_id())
		->where('status', '=', 'published')->count();
}