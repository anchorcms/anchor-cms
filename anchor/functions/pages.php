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
 * Returns the page content
 * (depreciated: should use either page_html or page_markdown)
 *
 * @return string
 */
function page_content() {
	return page_markdown();
}

/**
 * Returns the page content
 * (raw content that was entered into the database)
 *
 * @return string
 */
function page_html() {
	$raw = Registry::prop('page', 'content');

	// swap out shortcodes {{meta_key}}
	$parsed = parse($raw);

	return $parsed;
}

/**
 * Returns the page markdown
 * (content is parsed with markdown)
 *
 * @return string
 */
function page_markdown() {
	$raw = Registry::prop('page', 'content');

	// swap out shortcodes {{meta_key}}
	$parsed = parse($raw);

	$md = new Markdown;
	return $md->transform($parsed);
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
	$id = Registry::prop('page', 'id');

	if($extend = Extend::field('page', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}