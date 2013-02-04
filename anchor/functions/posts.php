<?php

/**
	Theme functions for posts
*/
function has_posts() {
	if( ! $total = Registry::get('total_posts')) {
		$query = Post::where('status', '=', 'published');

		// filter category
		if($category = Registry::get('post_category')) {
			$query->where('category', '=', $category->id);
		}

		$total = $query->count();

		Registry::set('total_posts', $total);
	}

	return $total;
}

function posts() {
	if( ! $total = Registry::get('total_posts')) {
		$total = has_posts();
	}

	if( ! $posts = Registry::get('posts')) {
		$per_page = Config::get('meta.posts_per_page');
		$offset = Registry::get('page_offset') ?: 1;
		$page = $offset - 1;


		$query = Post::where('status', '=', 'published');

		// filter category
		if($category = Registry::get('post_category')) {
			$query->where('category', '=', $category->id);
		}

		$posts = $query->order_by('created', 'desc')->take($per_page)->skip($page * $per_page)->get();

		$posts = new Items($posts);

		Registry::set('posts', $posts);
	}

	if($result = $posts->valid()) {
		// register single post
		Registry::set('article', $posts->current());

		// move to next
		$posts->next();
	}

	return $result;
}

function posts_prev($text = '&larr; Previous', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = floor($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$prev = $page + 1;

	$url = base_url($posts_page->slug . '/' . $prev);

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/' . $prev);
	}

	if(($page - 1) < $pages) {
		return '<a class="prev" href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function total_posts() {
	return Registry::get('total_posts');
}

function has_pagination() {
	return total_posts() > Config::get('meta.posts_per_page');
}

function posts_next($text = 'Next &rarr;', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = ceil($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$next = $page - 1;

	$url = base_url($posts_page->slug . '/' . $next);

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/' . $next);
	}

	if($offset > 0) {
		return '<a class="next" href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function posts_per_page() {
	return min(Registry::get('total_posts'), Config::get('meta.posts_per_page'));
}