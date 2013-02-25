<?php

// Home page and posts page
$posts_page = Registry::get('posts_page');
$home_page = Registry::get('home_page');

$callback = function($offset = 1) use($posts_page) {
	// get public listings
	list($total, $posts) = Post::listing(null, $offset, Config::meta('posts_per_page'));

	$posts = new Items($posts);

	Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);

	return new Template('posts');
};

/**
 * The home page is the post listing page.
 */
if($home_page->id == $posts_page->id) {
	Route::get(array('/', $posts_page->slug, $posts_page->slug . '/(:num)'), $callback);
}
else {
	/**
	 * The home page
	 */
	Route::get(array('/', $home_page->slug), function() use($home_page) {
		Registry::set('page', $home_page);

		return new Template('page');
	});

	/**
	 * The post listings page
	 */
	Route::get(array($posts_page->slug, $posts_page->slug . '/(:num)'), $callback);
}

/**
 * View posts by category
 */
Route::get(array('category/(:any)', 'category/(:any)/(:num)'), function($slug = '', $offset = 1) use($posts_page) {
	if( ! $category = Category::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	// get public listings
	list($total, $posts) = Post::listing($category, $offset, Config::meta('posts_per_page'));

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
Route::get($posts_page->slug . '/(:any)', function($slug) {
	if( ! $post = Post::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	Registry::set('article', $post);
	Registry::set('category', Category::find($post->category));

	return new Template('article');
});

/**
 * Post a comment
 */
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	$input = Input::get(array('name', 'email', 'text'));

	$validator = new Validator($input);

	$validator->check('email')
		->is_email(__('comments.missing_email', 'Please enter your email address'));

	$validator->check('text')
		->is_max(3, __('comments.missing_text', 'Please enter your comment'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
	}

	$input['post'] = Post::slug($slug)->id;
	$input['date'] = date('c');
	$input['status'] = Config::get('meta.auto_published_comments') ? 'approved' : 'pending';

	// remove bad tags
	$input['text'] = strip_tags($input['text'], '<a>,<b>,<blockquote>,<code>,<em>,<i>,<p>,<pre>');

	// check if the comment is possibly spam
	if($spam = Comment::spam($input)) {
		$input['status'] = 'spam';
	}

	$input['id'] = Comment::create($input);

	Notify::success(__('comments.created', 'Your comment has been added.'));

	// dont notify if we have marked as spam
	if( ! $spam and Config::get('meta.comment_notifications')) {
		Comment::notify($input);
	}

	return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
});

/**
 * Rss feed
 */
Route::get('feeds/rss', function() {
	$uri = 'http://' . $_SERVER['HTTP_HOST'];
	$rss = new Rss(Config::meta('sitename'), Config::meta('description'), $uri, Config::app('language'));

	$query = Post::where('status', '=', 'published');

	foreach($query->get() as $article) {
		$rss->item($article->title, $article->slug, $article->description, $article->created);
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
	$term = Session::get($slug);

	list($total, $posts) = Post::search($term, $offset, Config::meta('posts_per_page'));

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

	Session::put(slug($term), $term);

	return Response::redirect('search/' . slug($term));
});

/**
 * View pages/catch all
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