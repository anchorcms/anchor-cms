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

function article_previous_url() {
	$page = Registry::get('posts_page');
	$query = Post::where('created', '<', Registry::prop('article', 'created'))
				->where('status', '!=', 'draft');

	if($query->count()) {
		$article = $query->sort('created', 'desc')->fetch();
		$page = Registry::get('posts_page');

		return base_url($page->slug . '/' . $article->slug);
	}
}

function article_next_url() {
	$page = Registry::get('posts_page');
	$query = Post::where('created', '>', Registry::prop('article', 'created'))
				->where('status', '!=', 'draft');

	if($query->count()) {
		$article = $query->sort('created', 'asc')->fetch();
		$page = Registry::get('posts_page');

		return base_url($page->slug . '/' . $article->slug);
	}
}

function article_url() {
	$page = Registry::get('posts_page');

	return base_url($page->slug . '/' . article_slug());
}

function article_description() {
	return Registry::prop('article', 'description');
}

function article_html() {
	return parse(Registry::prop('article', 'html'), false);
}

function article_markdown() {
	return parse(Registry::prop('article', 'html'));
}

function article_css() {
	return Registry::prop('article', 'css');
}

function article_js() {
	return Registry::prop('article', 'js');
}

function article_time() {
	if($created = Registry::prop('article', 'created')) {
		return Date::format($created, 'U');
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
	return Registry::prop('article', 'author_name');
}

function article_author_id() {
	return Registry::prop('article', 'author_id');
}

function article_author_bio() {
	return Registry::prop('article', 'author_bio');
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
