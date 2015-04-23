<?php

/*
	Theme functions for menus
*/
/**
 * Are there any menu items?
 * @return boolean
 */
function has_menu_items() {
	return Registry::get('total_menu_items');
}

function menu_items() {
	$pages = Registry::get('menu');

	if($result = $pages->valid()) {
		Registry::set('menu_item', $pages->current());

		$pages->next();
	}

	// back to the start
	if( ! $result) $pages->rewind();

	return $result;
}

/*
	Object props
*/
/**
 * Grab the ID of this menu item
 * @return int
 */
function menu_id() {
	return Registry::prop('menu_item', 'id');
}

/**
 * Grab the url of this menu item
 * @return String
 */
function menu_url() {
	if($page = Registry::get('menu_item')) {
		return $page->uri();
	}
}


function menu_relative_url() {
	if($page = Registry::get('menu_item')) {
		return $page->relative_uri();
	}
}

/**
 * Grab the name of this menu item
 * @return String
 */
function menu_name() {
	return Registry::prop('menu_item', 'name');
}

/**
 * Grab the title of this menu item
 * @return String
 */
function menu_title() {
	return Registry::prop('menu_item', 'title');
}

/**
 * Is the page of this menu, the page we have loaded?
 * @return boolean
 */
function menu_active() {
	if($page = Registry::get('menu_item')) {
		return $page->active();
	}
}

/**
 * Grab the parent menu item of this menu item
 * @return Menu
 */
function menu_parent() {
	return Registry::prop('menu_item', 'parent');
}

/*
	HTML Builders
*/
function menu_render($params = array()) {
	$html = '';
	$menu = Registry::get('menu');

	// options
	$parent = isset($params['parent']) ? $params['parent'] : 0;
	$class = isset($params['class']) ? $params['class'] : 'active';

	foreach($menu as $item) {
		if($item->parent == $parent) {
			$attr = array();

			if($item->active()) $attr['class'] = $class;

			$html .= '<li>';
			$html .= Html::link($item->relative_uri(), $item->name, $attr);
			$html .= menu_render(array('parent' => $item->id));
			$html .= '</li>' . PHP_EOL;
		}
	}

	if($html) $html = PHP_EOL . '<ul>' . PHP_EOL . $html . '</ul>' . PHP_EOL;

	return $html;
}