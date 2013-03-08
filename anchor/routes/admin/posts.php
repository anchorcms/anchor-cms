<?php

/*
	List all posts and paginate through them
*/
Route::get(array('admin/posts', 'admin/posts/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['posts'] = Post::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('posts/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit post
*/
Route::get('admin/posts/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['article'] = Post::find($id);
	$vars['page'] = Registry::get('posts_page');

	// extended fields
	$vars['fields'] = Extend::fields('post', $id);

	$vars['statuses'] = array(
		'published' => __('posts.published', 'Published'),
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived')
	);

	$vars['categories'] = Category::dropdown();

	return View::create('posts/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer')
		->partial('editor', 'partials/editor');
}));

Route::post('admin/posts/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/edit/' . $id);
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	if($input['created']) {
		$input['created'] = Date::format($input['created'], 'Y-m-d H:i:s');
	}
	else {
		unset($input['created']);
	}

	if(is_null($input['comments'])) {
		$input['comments'] = 0;
	}

	if(empty($input['html'])) {
		$input['status'] = 'draft';
	}

	Post::update($id, $input);

	Extend::process('post', $id);

	Notify::success(__('posts.updated', 'Your article has been updated.'));

	return Response::redirect('admin/posts/edit/' . $id);
}));

/*
	Add new post
*/
Route::get('admin/posts/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['page'] = Registry::get('posts_page');

	// extended fields
	$vars['fields'] = Extend::fields('post');

	$vars['statuses'] = array(
		'published' => __('posts.published', 'Published'),
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived')
	);

	$vars['categories'] = Category::dropdown();

	return View::create('posts/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer')
		->partial('editor', 'partials/editor');
}));

Route::post('admin/posts/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/add');
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	if(empty($input['created'])) {
		$input['created'] = date('Y-m-d H:i:s');
	}

	$user = Auth::user();

	$input['author'] = $user->id;

	if(is_null($input['comments'])) {
		$input['comments'] = 0;
	}

	if(empty($input['html'])) {
		$input['status'] = 'draft';
	}

	$post = Post::create($input);

	Extend::process('post', $post->id);

	$message = __('posts.created', 'Your new article was created, <a href="%s">continue editing</a>.');

	Notify::success(sprintf($message, Uri::to('posts/edit/' . $post->id)));

	return Response::redirect('admin/posts');
}));

/*
	Preview post
*/
Route::post('admin/posts/preview', array('before' => 'auth', 'main' => function() {
	$html = Input::get('html');

	// apply markdown processing
	$md = new Markdown;
	$output = Json::encode(array('html' => $md->transform($html)));

	return Response::create($output, 200, array('Content-Type' => 'application/json'));
}));

/*
	Delete post
*/
Route::get('admin/posts/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	Post::find($id)->delete();

	Comment::where('post', '=', $id)->delete();

	Query::table('post_meta')->where('post', '=', $id)->delete();

	Notify::success(__('posts.post_success_deleted'));

	return Response::redirect('admin/posts');
}));