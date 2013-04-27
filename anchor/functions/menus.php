<?php

/*
	Theme functions for menus
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
function menu_id() {
	return Registry::prop('menu_item', 'id');
}

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

function menu_name() {
	return Registry::prop('menu_item', 'name');
}

function menu_title() {
	return Registry::prop('menu_item', 'title');
}

function menu_active() {
	if($page = Registry::get('menu_item')) {
		return $page->active();
	}
}

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