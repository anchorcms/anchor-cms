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
	if( ! $posts = Registry::get('posts')) {
		$per_page = Config::get('meta.posts_per_page');
		$page = Registry::get('page_offset') - 1;

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

function posts_next($text = 'Next', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = floor($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$next = $page + 1;

	$url = base_url($posts_page->slug . '/' . $next);

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/' . $next);
	}

	if(($page - 1) < $pages) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}

function posts_prev($text = 'Previous', $default = '') {
	$per_page = Config::get('meta.posts_per_page');
	$page = Registry::get('page_offset');

	$offset = ($page - 1) * $per_page;
	$total = Registry::get('total_posts');

	$pages = ceil($total / $per_page);

	$posts_page = Registry::get('posts_page');
	$prev = $page - 1;

	$url = base_url($posts_page->slug . '/' . $prev);

	// filter category
	if($category = Registry::get('post_category')) {
		$url = base_url('category/' . $category->slug . '/' . $prev);
	}

	if($offset > 0) {
		return '<a href="' . $url . '">' . $text . '</a>';
	}

	return $default;
}