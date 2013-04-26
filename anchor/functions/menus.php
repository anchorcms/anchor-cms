<?php

/**
 * Returns the number of items in the menu
 *
 * @return string
 */
function has_menu_items() {
	return Registry::get('total_menu_items');
}

/**
 * Returns true while there are still items in the array.
 *
 * Updates the current menu_item object in the Registry on each call.
 *
 * @return bool
 */
function menu_items() {
	if($pages = Registry::get('menu')) {
		if($result = $pages->valid()) {
			Registry::set('menu_item', $pages->current());

			$pages->next();
		}
		// back to the start
		else $pages->rewind();

		return $result;
	}
}

/**
 * Returns the menu_item ID
 *
 * @return string
 */
function menu_id() {
	return Registry::prop('menu_item', 'id');
}

/**
 * Returns the menu_item url
 *
 * @return string
 */
function menu_url() {
	if($page = Registry::get('menu_item')) {
		return $page->uri();
	}
}

/**
 * Returns the menu_item relative url (slug and parent slugs)
 *
 * @return string
 */
function menu_relative_url() {
	if($page = Registry::get('menu_item')) {
		return $page->relative_uri();
	}
}

/**
 * Returns the menu_item name (short title)
 *
 * @return string
 */
function menu_name() {
	return Registry::prop('menu_item', 'name');
}

/**
 * Returns the menu_item title
 *
 * @return string
 */
function menu_title() {
	return Registry::prop('menu_item', 'title');
}

/**
 * Returns true if the current slug matches the menu_item slug
 *
 * @return bool
 */
function menu_active() {
	if($page = Registry::get('menu_item')) {
		return $page->active();
	}
}

/**
 * Returns the menu_item parent ID
 *
 * @return string
 */
function menu_parent() {
	return Registry::prop('menu_item', 'parent');
}

/**
 * Renders a unodered list as a menu including any sub menus
 *
 * @param array array('parent' => 0, 'class' => '')
 * @return string
 */
function menu_render($params = array()) {
	$html = '';
	$menu = clone Registry::get('menu');

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