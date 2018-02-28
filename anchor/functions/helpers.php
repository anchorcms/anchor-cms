<?php

use System\config;
use System\uri;

/**************************
 * Theme helpers functions
 **************************/

/**
 * Retrieves the full URL to a URI
 *
 * @param string $url
 *
 * @return string
 */
function full_url($url = '')
{
    return Uri::full($url);
}

/**
 * Retrieves the base URL to a URI
 *
 * @param string $url
 *
 * @return string
 */
function base_url($url = '')
{
    return Uri::to($url);
}

/**
 * Retrieves the theme URL
 *
 * @param string $file (optional) file path to append
 *
 * @return string
 */
function theme_url($file = '')
{
    $theme_folder = Config::meta('theme');
    $base         = 'themes' . '/' . $theme_folder . '/';

    return asset($base . ltrim($file, '/'));
}

/**
 * Require a theme file
 *
 * @param string $file path to file to include
 *
 * @return mixed
 */
function theme_include($file)
{
    $theme_folder = Config::meta('theme');
    $base         = PATH . 'themes' . DS . $theme_folder . DS;

    if (is_readable($path = $base . ltrim($file, DS) . EXT)) {
        /** @noinspection PhpIncludeInspection */
        return require $path;
    }
}

/**
 * Retrieve the URL to an asset
 *
 * @param string $extra (optional) string to append
 *
 * @return string
 */
function asset_url($extra = '')
{
    return asset('anchor/views/assets/' . ltrim($extra, '/'));
}

/**
 * Retrieves the escaped current URL
 *
 * @return string
 * @throws \ErrorException
 * @throws \OverflowException
 */
function current_url()
{
    return htmlentities(raw_current_url());
}

/**
 * Retrieves the raw current URL
 *
 * @return string
 * @throws \ErrorException
 * @throws \OverflowException
 */
function raw_current_url()
{
    return Uri::current();
}

/**
 * Retrieves the RSS URL
 *
 * @return string
 */
function rss_url()
{
    return base_url('feeds/rss');
}

/**************************
 * Custom function helpers
 **************************/

/**
 * Binds an event to the page
 *
 * @param string   $page
 * @param \Closure $fn
 *
 * @return void
 */
function bind($page, $fn)
{
    Events::bind($page, $fn);
}

/**
 * Receives an event
 *
 * @param string $name
 *
 * @return string
 */
function receive($name = '')
{
    return Events::call($name);
}

/**
 * Retrieves all body CSS classes
 *
 * @return string
 */
function body_class()
{
    $classes = [];

    //  Is it a posts page?
    if (is_postspage()) {
        $classes[] = 'posts';
    }

    //  Is it the homepage?
    if (is_homepage()) {
        $classes[] = 'home';
    }

    //  Is it a single post?
    if (is_article()) {
        $classes[] = 'article';
    }

    //  Is it a custom page?
    if (is_page()) {
        $classes[] = 'page';
    }

    return implode(' ', array_unique($classes));
}

/********************
 * page type helpers
 ********************/

/**
 * Whether the current page is the home page
 *
 * @return bool
 */
function is_homepage()
{
    return Registry::prop('page', 'id') == Config::meta('home_page');
}

/**
 * Whether the current page is the posts page
 *
 * @return bool
 */
function is_postspage()
{
    return Registry::prop('page', 'id') == Config::meta('posts_page');
}

/**
 * Whether the current page is an article
 *
 * @return bool
 */
function is_article()
{
    return Registry::get('article') !== null;
}

/**
 * Whether the current page is a page
 *
 * @return bool
 */
function is_page()
{
    return Registry::get('page') !== null;
}
