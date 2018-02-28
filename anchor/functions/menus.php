<?php

/****************************
 * Theme functions for menus
 ****************************/

/**
 * Checks whether the menu has any items
 *
 * @return bool
 */
function has_menu_items()
{
    return Registry::get('total_menu_items');
}

/**
 * Menu items iterator
 *
 * @return bool
 */
function menu_items()
{
    // get all pages in the menu
    /** @var \items $pages */
    $pages = Registry::get('menu');

    if ($result = $pages->valid()) {
        Registry::set('menu_item', $pages->current());

        $pages->next();
    }

    // back to the start
    if ( ! $result) {
        $pages->rewind();
    }

    return $result;
}

/**
 * Retrieves the ID of a menu item
 *
 * @param \page|array|null $menu_item menu item to retrieve an ID for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return mixed|null
 */
function menu_id($menu_item = null)
{
    if (is_array($menu_item)) {
        return $menu_item['id'];
    }

    return ($menu_item
        ? $menu_item->id
        : Registry::prop('menu_item', 'id')
    );
}

/**
 * Retrieves the URL of a menu item
 *
 * @param \page|array|null $menu_item menu item to retrieve a URL for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return string
 * @throws \Exception
 */
function menu_url($menu_item = null)
{
    if (is_array($menu_item)) {
        $menu_item = get_menu_item('id', $menu_item);
    }

    return ($menu_item
        ? $menu_item->uri()
        : Registry::get('menu_item')->uri()
    );
}

/**
 * Retrieves the relative menu item URL
 *
 * @param \page|array|null $menu_item menu item to retrieve a URL for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return string
 * @throws \Exception
 */
function menu_relative_url($menu_item = null)
{
    if (is_array($menu_item)) {
        $menu_item = get_menu_item('id', $menu_item);
    }

    return ($menu_item
        ? $menu_item->relative_uri()
        : Registry::get('menu_item')->relative_uri()
    );
}

/**
 * Retrieves the name of a menu item
 *
 * @param \page|array|null $menu_item menu item to retrieve a name for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return string
 */
function menu_name($menu_item = null)
{
    if (is_array($menu_item)) {
        return $menu_item['name'];
    }

    return ($menu_item
        ? $menu_item->name
        : Registry::prop('menu_item', 'name')
    );
}

/**
 * Retrieves the title of a menu item.
 *
 * @param \page|array|null $menu_item menu item to retrieve a title for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return string
 */
function menu_title($menu_item = null)
{
    if (is_array($menu_item)) {
        return $menu_item['title'];
    }

    return ($menu_item
        ? $menu_item->title
        : Registry::prop('menu_item', 'title')
    );
}

/**
 * Checks whether a menu item is active
 *
 * @param \page|array|null $menu_item menu item to check status for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return bool
 */
function menu_active($menu_item = null)
{
    if (is_array($menu_item)) {
        $menu_item = get_menu_item('id', $menu_item);
    }

    return ($menu_item
        ? $menu_item->active()
        : Registry::get('menu_item')->active()
    );
}

/**
 * Retrieves the parent ID of a menu item
 *
 * @param \page|array|null $menu_item menu item to retrieve a parent ID for. If no item is passed,
 *                                    will use the current menu item
 *
 * @return int
 */
function menu_parent($menu_item = null)
{
    if (is_array($menu_item)) {
        return $menu_item['parent'];
    }

    return ($menu_item
        ? $menu_item->parent
        : Registry::prop('menu_item', 'parent')
    );
}

/**
 * Checks whether a menu item has children
 *
 * @param \page|array|null $parent menu item to check for children items. If no item is passed,
 *                                 will use the current menu item
 *
 * @return bool
 */
function menu_has_children($parent = null)
{
    return count(get_menus_children($parent)) > 0;
}

/**
 * Retrieves all children of a menu item
 *
 * @param \page|array|null $parent menu item to retrieve children items for. If no item is passed,
 *                                 will use the current menu item
 *
 * @return array
 */
function get_menus_children($parent = null)
{
    $menu_item = menu_id($parent);
    $menu      = Registry::get('menu');
    $menu->rewind();
    $children = [];

    foreach ($menu as $item) {
        if ($item->parent == $menu_item) {
            $children[] = $item;
        }
    }

    return $children;
}

/**
 * Checks whether a menu item has at least one active child
 *
 * @param \page|array|null $parent menu item to check for active children items.
 *                                 If no item is passed, will use the current menu item
 *
 * @return bool
 */
function is_child_active($parent = null)
{
    foreach (get_menus_children($parent) as $child) {
        if ($child->active()) {
            return true;
        }
    }

    return false;
}

/**
 * Retrieves a menu item feature (page model property)
 *
 * @param array|string $feature_type feature type or feature types to retrieve
 * @param string       $menu_feature feature to retrieve
 *
 * @return mixed
 */
function get_menu_item($feature_type, $menu_feature)
{
    if (is_array($menu_feature)) {
        $menu_feature = $menu_feature[$feature_type];
    }

    $menu = clone Registry::get('menu');

    foreach ($menu as $page) {
        if ($page->{$feature_type} == $menu_feature) {
            return $page;
        }
    }
}

/**
 * Renders a menu
 *
 * @param array $params menu rendering parameters. Available are "parent" (parent menu item ID),
 *                      "class" (additional CSS class for the active menu item) and
 *                      "index" (Specifying the index at which we start rendering)
 *
 * @return string HTML output
 * @throws \Exception
 */
function menu_render($params = [])
{
    $html = '';

    /** @var \items $menu */
    $menu = Registry::get('menu');

    // options
    $parent = isset($params['parent']) ? $params['parent'] : 0;
    $class  = isset($params['class']) ? $params['class'] : 'active';
    $index  = isset($params['index']) ? $params['index'] : 0;

    /** @var \page $item */
    foreach ($menu as $item) {
        if ($item->parent == $parent) {
            $attr = [];

            if ($item->active()) {
                $attr['class'] = $class;
            }

            $html .= '<li>';
            $html .= Html::link($item->relative_uri(), $item->name, $attr);
            $html .= menu_render(['parent' => $item->id, 'index' => $menu->key()]);
            $html .= '</li>';
        }
    }

    // Reset our index before returning
    $menu->rewind();
    $menu->next();

    while ($index > 1) {
        $menu->next();
        $index--;
    }

    if ($html) {
        $html = '<ul>' . $html . '</ul>';
    }

    return $html;
}
