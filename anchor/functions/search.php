<?php

/******************************
 *  Theme functions for search
 ******************************/

use System\config;
use System\session;

/**
 * Checks whether there are any search results
 *
 * @return bool
 */
function has_search_results()
{
    return Registry::get('total_posts', 0) > 0;
}

/**
 * Retrieves the total number of search results
 *
 * @return int
 */
function total_search_results()
{
    return Registry::get('total_posts', 0);
}

/**
 * Loops through the search results
 *
 * @return bool
 */
function search_results()
{
    /** @var \items $posts */
    $posts = Registry::get('search_results');

    if ($result = $posts->valid()) {

        // register single post
        Registry::set('search_item', $posts->current());

        // move to next
        $posts->next();
    }

    return $result;
}

/**
 * Retrieves the search term
 *
 * @return string
 */
function search_term()
{
    return Registry::get('search_term');
}

/**
 * Checks whether the search results are paginated
 *
 * @return bool
 */
function has_search_pagination()
{
    return Registry::get('total_posts') > Config::meta('posts_per_page');
}

/**
 * Retrieves the next search results page link
 *
 * @param string $text    (optional) link text
 * @param string $default (optional) default if no next link
 *
 * @return string
 */
function search_next($text = 'Next', $default = '')
{
    $per_page    = Config::meta('posts_per_page');
    $page        = Registry::get('page_offset');

    $total       = Registry::get('total_posts');
    $pages       = floor($total / $per_page);
    $search_page = Registry::get('page');
    $next        = $page + 1;
    $term        = Registry::get('search_term');

    Session::put(slug($term), $term);

    $url = base_url($search_page->slug . '/' . $term . '/' . $next);

    if (($page - 1) < $pages) {
        return '<a href="' . $url . '">' . $text . '</a>';
    }

    return $default;
}

/**
 * Retrieves the previous search results page link
 *
 * @param string $text    (optional) link text
 * @param string $default (optional) default if no next link
 *
 * @return string
 */
function search_prev($text = 'Previous', $default = '')
{
    $per_page    = Config::meta('posts_per_page');
    $page        = Registry::get('page_offset');
    $offset      = ($page - 1) * $per_page;
    $total       = Registry::get('total_posts');

    $search_page = Registry::get('page');
    $prev        = $page - 1;
    $term        = Registry::get('search_term');

    Session::put(slug($term), $term);

    $url = base_url($search_page->slug . '/' . $term . '/' . $prev);

    if ($offset > 0) {
        return '<a href="' . $url . '">' . $text . '</a>';
    }

    return $default;
}

/**
 * Retrieves the search URL
 *
 * @return string
 */
function search_url()
{
    return base_url('search');
}

/**
 * Generates the search input field
 *
 * @param string $extra (optional) additional HTML attributes for the input tag
 *
 * @return string
 */
function search_form_input($extra = '')
{
    return '<input name="term" type="text" ' . $extra . ' value="' . search_term() . '">';
}

/**
 * Get the search item object from the registry.
 *
 * @return Object
 */
function search_item()
{
    return Registry::get('search_item');
}

/**
 * Retrieves the search item type (post/page/other)
 *
 * @return string
 */
function search_item_type()
{
    $item_class = strtolower(get_class(search_item()));
    if ($item_class == 'page') {
        return 'page';
    } elseif ($item_class == 'post') {
        return 'post';
    } else {
        return 'unknown';
    }
}

/**
 * Retrieves the current search item ID
 *
 * @return int
 */
function search_item_id()
{
    $item = search_item();

    if ($item) {
        return $item->id;
    }
}

/**
 * Retrieves the current search item title
 *
 * @return string
 */
function search_item_title()
{
    $item = search_item();

    if ($item) {
        return $item->title;
    }
}

/**
 * Retrieves the current search item name
 *
 * @return string
 */
function search_item_name()
{
    $item = search_item();

    if ($item) {
        return $item->name;
    }
}

/**
 * Retrieves the current search item slug
 *
 * @return string
 */
function search_item_slug()
{
    $item = search_item();

    if ($item) {
        return $item->slug;
    }
}

/**
 * Retrieves the current search item URL
 *
 * @return string
 */
function search_item_url()
{
    $item = search_item();
    $type = search_item_type();

    if ($type == 'page') {
        return $item->uri();
    }

    if ($type == 'post') {
        return base_url(Registry::get('posts_page')->slug) . '/' . search_item_slug();
    }
}
