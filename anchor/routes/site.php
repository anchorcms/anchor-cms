<?php

/*
	Home page and posts page
*/
$posts_page = Registry::get('posts_page');

$home_page = Registry::get('home_page');

$callback = function($page = 1) use($posts_page) {
	Registry::set('page', $posts_page);

	Registry::set('page_offset', $page);

	return new Template('posts');
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
		Registry::set('page', $home_page);

		return new Template('page');
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
		return Response::make(new Template('404'), 404);
	}

	Registry::set('page', $posts_page);

	Registry::set('page_offset', $page);

	Registry::set('post_category', $category);

	return new Template('posts');
});

/*
	View article
*/
Route::get($posts_page->slug . '/(:any)', function($slug) {
	if( ! $post = Post::slug($slug)) {
		return Response::make(new Template('404'), 404);
	}

	Registry::set('article', $post);

	Registry::set('category', Category::find($post->category));

	return new Template('article');
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

Route::get('feeds/json', function() {
	return json_encode(array(
		'meta' => Config::get('meta'),
		'posts' => Post::where('status', '=', 'published')->get()
	));
});

/*
	Search
*/
Route::get(array('search', 'search/(:any)', 'search/(:any)/(:num)'), function($id = '', $offset = 1) {
	$page = new Page;
	$page->title = 'Search';

	Registry::set('page', $page);

	Registry::set('page_offset', $offset);

	Registry::set('search_term', $id);

	return new Template('search');
});

Route::post('search', function() {
	// search and save search ID
	$term = Input::get('term');

	Session::put('search_term', $term);

	return Response::redirect('search/' . $term);
});

/*
	View pages
*/
Route::get('(:any)', function($slug) {
	if( ! $page = Page::slug($slug)) {
		return Response::make(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	return new Template('page');
});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});