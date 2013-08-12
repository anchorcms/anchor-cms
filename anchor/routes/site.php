<?php

/**
 * Important pages
 */
$home_page = Registry::get('home_page');
$posts_page = Registry::get('posts_page');

/**
 * The Home page
 */
if($home_page->id != $posts_page->id) { // home page is different to the posts page
	Route::get(array('/', $home_page->slug), function() use($home_page) {
		Registry::set('page', $home_page);

		$template = new Template('page');
    	if($template->exists('page-' . $home_page->slug)) {
   			$template->set('page-' . $home_page->slug);
   		}
		return $template;
	});
}

/**
 * Post listings page
 */
$routes = array($posts_page->slug, $posts_page->slug . '/(:num)');

// home page is the posts page
if($home_page->id == $posts_page->id) $routes[] = '/';

Route::get($routes, function($page_number = 1) use($posts_page) {
	// get public listings
	list($total, $posts) = Post::listing(null, $page_number, $per_page = Config::meta('posts_per_page'));

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($page_number > $max_page) or ($page_number < 1)) {
		return Anchor::page_not_found();
	}

	Registry::set(array(
		'posts' => new Items($posts),
		'total_posts' => $total,
		'page' => $posts_page,
		'page_offset' => $page_number));

	return new Template('posts');
});

/**
 * View posts by category
 */
Route::get(array('category/(:any)', 'category/(:any)/(:num)'), function($slug = '', $page_number = 1) use($posts_page) {

	if( ! $category = Category::slug($slug)) {
		return Anchor::page_not_found();
	}

	// get public listings
	list($total, $posts) = Post::listing($category, $page_number, $per_page = Config::meta('posts_per_page'));

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($page_number > $max_page) or ($page_number < 1)) {
		return Response::create(new Template('404'), 404);
	}

	Registry::set(array(
		'posts' => new Items($posts),
		'total_posts' => $total,
		'page' => $posts_page,
		'page_offset' => $page_number,
		'post_category' => $category));

	$template = new Template('posts');

	if($template->exists('category')) {
		$template->set('category');
	}
	elseif($template->exists('category-' . $category->slug)) {
		$template->set('category-' . $category->slug);
	}

	return $template;
});

/**
 * Redirect by article ID
 */
Route::get('(:num)', function($id) use($posts_page) {
	if( ! $post = Post::find($id)) {
		return Anchor::page_not_found();
	}

	return Response::redirect($posts_page->slug . '/' . $post->slug);
});

/**
 * View article
 */
Route::get($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug)) {
		return Anchor::page_not_found();
	}

	Registry::set('page', $posts_page);
	Registry::set('article', $post);
	Registry::set('category', Category::find($post->category));

	$template = new Template('article');

	if($template->exists('article-' . $post->slug)) {
		$template->set('article-' . $post->slug);
	}

	return $template;
});

/**
 * Post a comment
 */
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug)) {
		return Anchor::page_not_found();
	}

	// comments disabled
	if( ! $post->comments) {
		return Response::redirect($posts_page->slug . '/' . $slug);
	}

	$input = Comment::input();

	$validator = Comment::validate($input);

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
	}

	// set the post ID
	$input['post'] = $post->id;

	$comment = Comment::create($input);

	Notify::success(__('comments.created'));

	// dont notify if we have marked as spam
	if( ! $spam and Config::meta('comment_notifications')) {
		$comment->notify();
	}

	return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
});

Route::get('like/(:any)', function($slug) {
	if (Post::like($slug)) {
		return Response::redirect($posts_page->slug . '/' . $slug);
	}
});

/**
 * Rss feed
 */
Route::get(array('rss', 'feeds/rss'), function() {
	$uri = 'http://' . $_SERVER['HTTP_HOST'];
	$rss = new Rss(Config::meta('sitename'), Config::meta('description'), $uri, Config::app('language'));

	$query = Post::where('status', '=', 'published')->sort('created', 'desc');

	foreach($query->get() as $article) {
		$rss->item(
			$article->title,
			Uri::full(Registry::get('posts_page')->slug . '/' . $article->slug),
			$article->content(),
			$article->created
		);
	}

	$xml = $rss->output();

	return Response::create($xml, 200, array('content-type' => 'application/xml'));
});

/**
 * Json feed
 */
Route::get('feeds/json', function() {
	$json = Json::encode(array(
		'meta' => Config::get('meta'),
		'posts' => Post::where('status', '=', 'published')->sort('created', 'desc')->get()
	));

	return Response::create($json, 200, array('content-type' => 'application/json'));
});

/**
 * Search
 */
Route::get('search', function() {
	// mock search page
	$page = new Page;
	$page->id = 0;
	$page->title = 'Search';
	$page->slug = 'search';

	// get search term
	$page_number = Input::get('page', 1);
	$term = filter_var(Input::get('term', ''), FILTER_SANITIZE_STRING);

	list($total, $posts) = Post::search($term, $page_number, Config::meta('posts_per_page'));

	// pagination
	$per_page = Config::get('meta.posts_per_page');
	$pages = floor($total / $per_page);

	$page_prev = ($page_number > 0) ? $page_number - 1 : 0;
	$page_next = (($page_number - 1) < $pages) ? $page_number + 1 : 0;

	// search templating vars
	Registry::set(array(
		'page' => $page,
		'page_next' => $page_next,
		'page_prev' => $page_prev,
		'search_term' => $term,
		'search_results' => new Items($posts),
		'total_posts' => $total));

	return new Template('search');
});

/**
 * View pages
 */
Route::get('(:all)', function($uri) {
	$slug = basename($uri);

	if( ! $page = Page::slug($slug)) {
		return Anchor::page_not_found();
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	$template = new Template('page');

	if($template->exists('page-' . $page->slug)) {
		$template->set('page-' . $page->slug);
	}

	return $template;
});

/*
 * 404 not found
 */
Route::not_found(function() {
	return Anchor::page_not_found();
});