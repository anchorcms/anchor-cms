<?php

/*
	Theme functions for articles
*/
function article_id() {
	return Registry::prop('article', 'id');
}

function article_title() {
	return Registry::prop('article', 'title');
}

function article_slug() {
	return Registry::prop('article', 'slug');
}

function article_url() {
	if($slug = article_slug()) {
		$page = Registry::get('posts_page');

		return base_url($page->slug . '/' . $slug);
	}
}

function article_description() {
	return Registry::prop('article', 'description');
}

function article_html() {
	$html = Registry::prop('article', 'html');

	return Post::parse($html);
}

function article_css() {
	return Registry::prop('article', 'css');
}

function article_js() {
	return Registry::prop('article', 'js');
}

function article_time() {
	if($created = Registry::prop('article', 'created')) {
		return strtotime($created);
	}
}

function article_date() {
	if($created = Registry::prop('article', 'created')) {
		return Date::format($created);
	}
}

function article_status() {
	return Registry::prop('article', 'status');
}

function article_category() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return $categories[$category]->title;
	}
}

function article_category_slug() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return $categories[$category]->slug;
	}
}

function article_category_url() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return base_url('category/' . $categories[$category]->slug);
	}
}

function article_total_comments() {
	return Registry::prop('article', 'total_comments');
}

function article_author() {
	if($user = User::search(array('id' => article_author_id()))) {
		return $user->real_name;
	}

	return false;
}

function article_author_id() {
	return Registry::prop('article', 'author');
}

function article_author_bio() {
	return Registry::prop('article', 'bio');
}

function article_custom_field($key, $default = '') {
	$id = Registry::prop('article', 'id');

	if($extend = Extend::field('post', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}

function customised() {
	if($itm = Registry::get('article')) {
		return $itm->js or $itm->css;
	}

	return false;
}
