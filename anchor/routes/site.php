<?php

/**
 * Important pages
 */
$home_page = Registry::get('home_page');
$posts_page = Registry::get('posts_page');

/**
 * The Home page
 */
if($home_page->id != $posts_page->id) {
	Route::get(array('/', $home_page->slug), function() use($home_page) {
		Registry::set('page', $home_page);

		return new Template('page');
	});
}

/**
 * Post listings page
 */
$routes = array($posts_page->slug, $posts_page->slug . '/(:num)');

if($home_page->id == $posts_page->id) {
	array_unshift($routes, '/');
}

Route::get($routes, function($offset = 1) use($posts_page) {
	if($offset > 0) {
		// get public listings
		list($total, $posts) = Post::listing(null, $offset, $per_page = Config::meta('posts_per_page'));
	} else {
		return Response::create(new Template('404'), 404);
	}

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($offset > $max_page) or ($offset < 1)) {
		return Response::create(new Template('404'), 404);
	}

	$posts = new Items($posts);

	Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);

	return new Template('posts');
});

/**
 * View posts by category
 */
Route::get(array('category/(:any)', 'category/(:any)/(:num)'), function($slug = '', $offset = 1) use($posts_page) {
	if( ! $category = Category::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	// get public listings
	list($total, $posts) = Post::listing($category, $offset, $per_page = Config::meta('posts_per_page'));

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($offset > $max_page) or ($offset < 1)) {
		return Response::create(new Template('404'), 404);
	}

	$posts = new Items($posts);

	Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);
	Registry::set('post_category', $category);

	return new Template('posts');
});

/**
 * View article
 */
Route::get($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	Registry::set('page', $posts_page);
	Registry::set('article', $post);
	Registry::set('category', Category::find($post->category));

	return new Template('article');
});

/**
 * Post a comment
 */
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug) or ! $post->comments) {
		return Response::create(new Template('404'), 404);
	}

	$input = Input::get(array('name', 'email', 'text'));

	$validator = new Validator($input);

	$validator->check('email')
		->is_email(__('comments.email_missing'));

	$validator->check('text')
		->is_max(3, __('comments.text_missing'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
	}

	$input['post'] = Post::slug($slug)->id;
	$input['date'] = Date::mysql('now');
	$input['status'] = Config::meta('auto_published_comments') ? 'approved' : 'pending';

	// remove bad tags
	$input['text'] = strip_tags($input['text'], '<a>,<b>,<blockquote>,<code>,<em>,<i>,<p>,<pre>');

	// check if the comment is possibly spam
	if($spam = Comment::spam($input)) {
		$input['status'] = 'spam';
	}

	$comment = Comment::create($input);

	Notify::success(__('comments.created'));

	// dont notify if we have marked as spam
	if( ! $spam and Config::meta('comment_notifications')) {
		$comment->notify();
	}

	return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
});

/**
 * Rss feed
 */
Route::get(array('rss', 'feeds/rss'), function() {
	$uri = 'http://' . $_SERVER['HTTP_HOST'];
	$rss = new Rss(Config::meta('sitename'), Config::meta('description'), $uri, Config::app('language'));

	$query = Post::where('status', '=', 'published');

	foreach($query->get() as $article) {
		$rss->item(
			$article->title,
			Uri::full(Registry::get('posts_page')->slug . '/' . $article->slug),
			$article->description,
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
		'posts' => Post::where('status', '=', 'published')->get()
	));

	return Response::create($json, 200, array('content-type' => 'application/json'));
});

/**
 * Search
 */
Route::get(array('search', 'search/(:any)', 'search/(:any)/(:num)'), function($slug = '', $offset = 1) {
	// mock search page
	$page = new Page;
	$page->id = 0;
	$page->title = 'Search';
	$page->slug = 'search';

	// get search term
	$term = filter_var($slug, FILTER_SANITIZE_STRING);
	Session::put(slug($term), $term);
	//$term = Session::get($slug); //this was for POST only searches

	// revert double-dashes back to spaces
	$term = str_replace('--', ' ', $term);

	if($offset > 0) {
		list($total, $posts) = Post::search($term, $offset, Config::meta('posts_per_page'));
	} else {
		return Response::create(new Template('404'), 404);
	}

	// search templating vars
	Registry::set('page', $page);
	Registry::set('page_offset', $offset);
	Registry::set('search_term', $term);
	Registry::set('search_results', new Items($posts));
	Registry::set('total_posts', $total);

	return new Template('search');
});

Route::post('search', function() {
	// search and save search ID
	$term = filter_var(Input::get('term', ''), FILTER_SANITIZE_STRING);

	// replace spaces with double-dash to pass through url
	$term = str_replace(' ', '--', $term);

	Session::put(slug($term), $term);

	return Response::redirect('search/' . slug($term));
});

/**
 * View pages
 */
Route::get('(:all)', function($uri) {
	if( ! $page = Page::slug($slug = basename($uri))) {
		return Response::create(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	return new Template('page');
});