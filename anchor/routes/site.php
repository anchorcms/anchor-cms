<?php

/*
	Home page and posts page
*/
$posts_page = Registry::get('posts_page');

$home_page = Registry::get('home_page');

$callback = function($page = 1) use($posts_page) {
	$template = $posts_page->template ?: 'posts';

	Registry::set('page', $posts_page);

	Registry::set('page_offset', $page);

	return new Template($template);
};

if($home_page->id == $posts_page->id) {
	/*
		View home page and posts and paginate through them
	*/
	Route::get(array('/', $posts_page->slug, $posts_page->slug . '/(:num)'), $callback);
}
else {
	/*
		Default home page
	*/
	Route::get(array('/', $home_page->slug), function() use($home_page) {
		$template = $home_page->template ?: 'page';

		Registry::set('page', $home_page);

		return new Template($template);
	});

	/*
		View posts and paginate through them
	*/
	Route::get(array($posts_page->slug, $posts_page->slug . '/(:num)'), $callback);
}

/*
	View posts by category
*/
Route::get(array('category/(:any)', 'category/(:any)/(:num)'), function($slug, $page = 1) use($posts_page) {
	if( ! $category = Category::slug($slug)) {
		return Response::error(404);
	}

	$template = $posts_page->template ?: 'posts';

	Registry::set('page', $posts_page);

	Registry::set('page_offset', $page);

	Registry::set('post_category', $category);

	return new Template($template);
});

/*
	View article
*/
Route::get($posts_page->slug . '/(:any)', function($slug) {
	if( ! $post = Post::slug($slug)) {
		return Response::error(404);
	}

	$template = $post->template ?: 'article';

	Registry::set('article', $post);

	return new Template($template);
});

// add comments
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	$input = Input::get_array(array('name', 'email', 'text'));

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

	Comment::create($input);

	Notify::success(__('comments.created', 'Your comment has been added.'));

	return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
});

/*
	Rss feed
*/
Route::get('feeds/rss', function() {
	$rss = new Rss(Config::get('meta.sitename'), Config::get('meta.description'), Uri::build(), Config::get('application.language'));

	$query = Post::where('status', '=', 'published');

	foreach($query->get() as $article) {
		$rss->item($article->title, $article->slug, $article->description, $article->created);
	}

	$xml = $rss->output();

	return Response::make($xml, 200, array('content-type' => 'application/xml'));
});

/*
	Search
*/
Route::get(array('search', 'search/(:any)', 'search/(:any)/(:num)'), function($id = '', $offset = 1) {
	$page = new Page;
	$page->title = 'Search';

	Registry::set('page', $page);

	Registry::set('page_offset', $offset);

	// get search term
	$term = Session::get('search_' . $id);

	Registry::set('search_term', $term);

	return new Template('search');
});

Route::post('search', function() {
	// search and save search ID
	$term = Input::get('term');
	$id = Str::random(4);

	Session::put('search_' . $id, $term);

	return Response::redirect('search/' . $id);
});

/*
	View pages
*/
Route::get('(:any)', function($slug) {
	if( ! $page = Page::slug($slug)) {
		return Response::error(404);
	}

	$template = $page->template ?: 'page';

	Registry::set('page', $page);

	return new Template($template);
});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});