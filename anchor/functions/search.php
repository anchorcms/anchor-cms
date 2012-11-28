<?php

/**
	Theme functions for search
*/
function has_search_results() {
	if( ! $total = Registry::get('total_posts')) {
		$per_page = Config::get('meta.posts_per_page');
		$page = Registry::get('page_offset') - 1;
		$term = Registry::get('search_term');

		$query = Post::where('status', '=', 'published')
			->where('title', 'like', $term . '%');

		$total = $query->count();

		Registry::set('total_posts', $total);
	}

	return $total;
}

function total_search_results() {
	return has_search_results() ? Registry::get('total_posts') : 0;
}

function search_results() {
	if( ! $posts = Registry::get('search_results')) {
		$per_page = Config::get('meta.posts_per_page');
		$page = Registry::get('page_offset') - 1;
		$term = Registry::get('search_term');

		$posts = Post::where('status', '=', 'published')
			->where('title', 'like', $term . '%')
			->take($per_page)
			->skip($page * $per_page)
			->get();

		$posts = new Items($posts);

		Registry::set('search_results', $posts);
	}

	if($result = $posts->valid()) {
		// register single post
		Registry::set('article', $posts->current());

		// move to next
		$posts->next();
	}

	return $result;
}

function search_term() {
	return Registry::get('search_term');
}

function search_next($text = 'Next', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = floor($total / $per_page);

	$posts_page = Registry::get('page');
	$next = $page + 1;

	$url = base_url($posts_page->slug . '/' . $next);

	if(($page - 1) < $pages) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function search_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = ceil($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$prev = $page - 1;

	$url = base_url($posts_page->slug . '/' . $prev);

	if($offset > 0) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function search_form_input($extra = '') {
	return '<input name="term" type="text" ' . $extra . ' value="' . search_term() . '">';
}