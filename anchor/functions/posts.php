<?php

/****************************
 * Theme functions for posts
 ****************************/

use System\config;

/**
 * Checks whether there are any posts
 *
 * @return bool
 */
function has_posts()
{
    return Registry::get('total_posts', 0) > 0;
}

/**
 * Loops through all posts
 *
 * @return bool
 */
function posts()
{
    /** @var \items $posts */
    $posts = Registry::get('posts');

    if ($result = $posts->valid()) {

        // register single post
        Registry::set('article', $posts->current());

        // move to next
        $posts->next();
    } // back to the start
    else {
        $posts->rewind();
    }

    return $result;
}

/**
 * Generates the next post link
 *
 * @param string $text    (optional) link text
 * @param string $default (optional) default if no previous link
 * @param array  $attrs   (optional) link tag attributes
 *
 * @return string
 */
function posts_next($text = 'Next &rarr;', $default = '', $attrs = [])
{
    $total    = Registry::get('total_posts');
    $offset   = Registry::get('page_offset');
    $per_page = Config::meta('posts_per_page');
    $page     = Registry::get('page');
    $url      = base_url($page->slug . '/');

    // filter category
    if ($category = Registry::get('post_category')) {
        $url = base_url('category/' . $category->slug . '/');
    }

    $pagination = new Paginator([], $total, $offset, $per_page, $url);

    return $pagination->next_link($text, $default, $attrs);
}

/**
 * Generates the previous posts link
 *
 * @param string $text    (optional) link text
 * @param string $default (optional) default if no previous link
 * @param array  $attrs   (optional) link tag attributes
 *
 * @return string
 */
function posts_prev($text = '&larr; Previous', $default = '', $attrs = [])
{
    $total    = Registry::get('total_posts');
    $offset   = Registry::get('page_offset');
    $per_page = Config::meta('posts_per_page');
    $page     = Registry::get('page');
    $url      = base_url($page->slug . '/');

    // filter category
    if ($category = Registry::get('post_category')) {
        $url = base_url('category/' . $category->slug . '/');
    }

    $pagination = new Paginator([], $total, $offset, $per_page, $url);

    return $pagination->prev_link($text, $default, $attrs);
}

/**
 * Retrieves the total number of posts
 *
 * @return int
 */
function total_posts()
{
    return Registry::get('total_posts');
}

/**
 * Checks whether the post list is paginated (eg. longer than the maximum posts per page)
 *
 * @return bool
 */
function has_pagination()
{
    return Registry::get('total_posts') > Config::meta('posts_per_page');
}

/**
 * Retrieve the number of posts per page. Will be the lower value between all posts
 * and the page limit.
 *
 * @return int
 */
function posts_per_page()
{
    return min(Registry::get('total_posts'), Config::meta('posts_per_page'));
}
