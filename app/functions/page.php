<?php

function page_id() {
	global $app;

	return $app['theme']->getVar('page')->id;
}

function page_url() {
	global $app;

	return $app['url']->to($app['theme']->getVar('page')->slug);
}

function page_slug() {
	global $app;

	return $app['theme']->getVar('page')->slug;
}

function page_name() {
	global $app;

	return $app['theme']->getVar('page')->name;
}

function page_title($default = '') {
	global $app;

	return $app['theme']->getVar('page')->title;
}

function page_content() {
	global $app;

	return $app['theme']->getVar('page')->html;
}

function page_status() {
	global $app;

	return $app['theme']->getVar('page')->status;
}

function page_custom_field($key, $default = '') {
	global $app;

	$values = $app['services.customFields']->getFieldValues('page', page_id());

	return array_key_exists($key, $values) ? $values[$key] : $default;
}
