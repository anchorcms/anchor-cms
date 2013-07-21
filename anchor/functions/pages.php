<?php

/**
 * Returns the page ID
 *
 * @return string
 */
function page_id() {
	return Registry::prop('page', 'id');
}

/**
 * Returns the page url (including nested pages)
 *
 * @return string
 */
function page_url() {
	if($page = Registry::get('page')) {
		return $page->uri();
	}
}

/**
 * Returns the page slug
 *
 * @return string
 */
function page_slug() {
	return Registry::prop('page', 'slug');
}

/**
 * Returns the page name (short title to be used in menus)
 *
 * @return string
 */
function page_name() {
	return Registry::prop('page', 'name');
}

/**
 * Returns the page title
 *
 * @param string
 * @return string
 */
function page_title($default = '') {
	if($title = Registry::prop('article', 'title')) {
		return $title;
	}

	if($title = Registry::prop('page', 'title')) {
		return $title;
	}

	return $default;
}

/**
 * Alias page content
 *
 * @return string
 */
function page_html() {
	return page_content();
}

/**
 * Alias page content
 *
 * @return string
 */
function page_markdown() {
	return page_content();
}

/**
 * Returns the page content
 *
 * @return string
 */
function page_content() {
	return Registry::get('page')->content();
}

/**
 * Returns the page status (published, draft, archived)
 *
 * @return string
 */
function page_status() {
	return Registry::prop('page', 'status');
}

/**
 * Returns the value of a custom field for a page
 *
 * @param string
 * @param mixed
 * @return string
 */
function page_custom_field($key, $default = '') {
	return Registry::get('page')->custom_field($key, $default);
}