<?php

namespace Anchorcms;

function article_id() {
	global $app;

	return $app['theme']->getVar('content')->id;
}

function article_title() {
	global $app;

	return $app['theme']->getVar('content')->title;
}

function article_slug() {
	global $app;

	return $app['theme']->getVar('content')->slug;
}

function article_url() {
	global $app;

	$content = $app['theme']->getVar('content');

	$category = $content->getCategory();

	return full_url(sprintf('%s/%s', $category->slug, $content->slug));
}

function article_content() {
	global $app;

	return $app['theme']->getVar('content')->html;
}

function article_time() {
	global $app;

	$date = $app['theme']->getVar('content')->published;

	return DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('U');
}

function article_date() {
	global $app;

	return $app['theme']->getVar('content')->published;
}

function article_status() {
	global $app;

	return $app['theme']->getVar('content')->status;
}

function article_category() {
	global $app;

	return $app['theme']->getVar('content')->getCategory()->title;
}

function article_category_id() {
	global $app;

	return $app['theme']->getVar('content')->category;
}

function article_category_slug() {
	global $app;

	return $app['theme']->getVar('content')->getCategory()->slug;
}

function article_category_url() {
	global $app;

	return $app['url']->to('category/' . article_category_slug());
}

function article_author() {
	global $app;

	return $app['theme']->getVar('content')->getAuthor()->getName();
}

function article_author_id() {
	global $app;

	return $app['theme']->getVar('content')->author;
}

function article_author_bio() {
	global $app;

	return $app['theme']->getVar('content')->getAuthor()->bio;
}

function article_custom_field($key, $default = '') {
	global $app;

	$values = $app['services.customFields']->getFieldValues('post', article_id());

	return array_key_exists($key, $values) ? $values[$key] : $default;
}
