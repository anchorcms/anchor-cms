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
    if($home_page->redirect) {
      return Response::redirect($home_page->redirect);
    }

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
		list($total, $posts) = Post::listing(null, $offset, $per_page = Post::perPage());
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
	list($total, $posts) = Post::listing($category, $offset, $per_page = Post::perPage());

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
 * Redirect by article ID
 */
Route::get('(:num)', function($id) use($posts_page) {
	if( ! $post = Post::id($id)) {
		return Response::create(new Template('404'), 404);
	}

	return Response::redirect($posts_page->slug . '/' . $post->data['slug']);
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

	if($post->status != 'published') {
		if(!Auth::user()) {
			return Response::create(new Template('404'), 404);
		}
	}

	return new Template('article');
});

/**
 * Edit posts
*/
Route::get($posts_page->slug . '/(:any)/edit', function($slug) use($posts_page) {
    if (!$post = Post::slug($slug) or Auth::guest()) {
        return Response::create(new Template('404'), 404);
    }

    return Response::redirect('/admin/posts/edit/' . $post->id);
});

/**
 * Edit pages
*/
Route::get('(:all)/edit', function($slug) use($posts_page) {
    if (!$page = Page::slug($slug) or Auth::guest()) {
        return Response::create(new Template('404'), 404);
    }

    return Response::redirect('/admin/pages/edit/' . $page->id);
});

/**
 * Post a comment
 */
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug) or ! $post->comments) {
		return Response::create(new Template('404'), 404);
	}

	$input = filter_var_array(Input::get(array('name', 'email', 'text')), array(
		'name' => FILTER_SANITIZE_STRING,
		'email' => FILTER_SANITIZE_EMAIL,
		'text' => FILTER_SANITIZE_SPECIAL_CHARS
	));

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

	$query = Post::where('status', '=', 'published')->sort(Base::table('posts.created'), 'desc')->take(25);

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
		'posts' => Post::where('status', '=', 'published')->sort('created', 'desc')->take(25)->get()
	));

	return Response::create($json, 200, array('content-type' => 'application/json'));
});

/**
 * Search
 */
Route::get(array('search', 'search/(:any)', 'search/(:any)/(:any)', 'search/(:any)/(:any)/(:num)'), function($whatSearching = 'all', $slug = '', $offset = 1) {
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

	if($offset <= 0) {
		return Response::create(new Template('404'), 404);
	}

	// Posts, pages, or all
	if($whatSearching === 'posts') list($total, $results) = (Post::search($term, $offset, Post::perPage()));
	elseif($whatSearching === 'pages') list($total, $results) = Page::search($term, $offset);
	else {
		$postResults = Post::search($term, $offset, Post::perPage());
		$pageResults = Page::search($term, $offset);
		$total = $postResults[0] + $pageResults[0];
		$results = array_merge($postResults[1], $pageResults[1]);
	}
	
	// search templating vars
	Registry::set('page', $page);
	Registry::set('page_offset', $offset);
	Registry::set('search_term', $term);
	Registry::set('search_results', new Items($results));
	Registry::set('total_posts', $total);

	return new Template('search');
});

Route::post('search', function() {
	// Search term
	$term = filter_var(Input::get('term', ''), FILTER_SANITIZE_STRING); // sanitize search term
	$term = str_replace(' ', '--', $term); // replace spaces with double-dash to pass through url

	// What searching
	$whatSearch = Input::get('whatSearch', ''); // get what we are searching for
	$whatSearch = $whatSearch === 'posts' ? 'posts' : $whatSearch === 'pages' ? 'pages' : 'all'; // clamp the choices

	Session::put(slug($term), $term);
	Session::put($whatSearch, $whatSearch);

	return Response::redirect('search/' . $whatSearch . '/' . slug($term));
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

	if($page->status != 'published') {
		if(!Auth::user()) {
			return Response::create(new Template('404'), 404);
		}
	}

	return new Template('page');
});