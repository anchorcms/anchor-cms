<?php

/*********************************
 * Theme functions for categories
 *********************************/

use System\database\query;

/**
 * Retrieves the total number of categories in the database
 *
 * @return int number of categories
 */
function total_categories()
{
    if ( ! $categories = Registry::get('categories')) {
        $categories = Category::get();
        $categories = new Items($categories);

        Registry::set('categories', $categories);
    }

    return $categories->length();
}

/**
 * loop categories
 *
 * @return bool
 */
function categories()
{
    if ( ! total_categories()) {
        return false;
    }

    $items = Registry::get('categories');

    if ($result = $items->valid()) {

        // register single category
        Registry::set('category', $items->current());

        // move to next
        $items->next();
    }

    // back to the start
    if ( ! $result) {
        $items->rewind();
    }

    return $result;
}

/**
 * single categories
 *
 * @return int|null
 */
function category_id()
{
    return Registry::prop('category', 'id');
}

/**
 * Retrieves the current category title
 *
 * @return string|null
 */
function category_title()
{
    return Registry::prop('category', 'title');
}

/**
 * Retrieves the current category slug
 *
 * @return string|null
 */
function category_slug()
{
    return Registry::prop('category', 'slug');
}

/**
 * Retrieves the current category description
 *
 * @return string|null
 */
function category_description()
{
    return Registry::prop('category', 'description');
}

/**
 * Retrieves the current category URL
 *
 * @return mixed
 */
function category_url()
{
    return base_url('category/' . category_slug());
}

/**
 * Counts the number of published posts in the current category
 *
 * @return string
 * @throws \ErrorException
 * @throws \Exception
 */
function category_count()
{
    return Query::table(Base::table('posts'))
                ->where('category', '=', category_id())
                ->where('status', '=', 'published')
                ->count();
}

/**
 * @param string $key
 * @param string $default
 *
 * @return mixed|null|string
 * @throws \ErrorException
 * @throws \Exception
 */
function category_custom_field($key, $default = '')
{
    $id = Registry::prop('category', 'id');

    if ($extend = Extend::field('category', $key, $id)) {
        return Extend::value($extend, $default);
    }

    return $default;
}
