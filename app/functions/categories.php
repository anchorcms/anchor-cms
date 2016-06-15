<?php

namespace Anchorcms;

function total_categories() {
	global $app;

	return $app['theme']->getVar('categories')->count();
}

function categories() {
	global $app;

	return $app['theme']->getVar('categories')->loop();
}

function category_id() {
	global $app;

	return $app['theme']->getVar('categories')->id;
}

function category_title() {
	global $app;

	return $app['theme']->getVar('categories')->title;
}

function category_slug() {
	global $app;

	return $app['theme']->getVar('categories')->slug;
}

function category_description() {
	global $app;

	return $app['theme']->getVar('categories')->description;
}

function category_url() {
	global $app;

	return full_url('category/' . $app['theme']->getVar('categories')->slug);
}

function category_count() {
	global $app;

	return 0; //$app['theme']->getVar('categories')->postCount();
}
