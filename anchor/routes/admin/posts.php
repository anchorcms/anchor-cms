<?php

/*
	List all posts and paginate through them
*/
Route::get(array('admin/posts', 'admin/posts/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['posts'] = Post::paginate($page, Config::get('meta.posts_per_page'));

	return View::make('posts/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

/*
	Edit post
*/
Route::get('admin/posts/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
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

	return View::make('posts/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/posts/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('html')
		->is_max(3, __('posts.missing_html', 'Please enter your html'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/edit/' . $id);
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	if($input['created']) {
		$input['created'] = Date::format($input['created'], 'c');
	}
	else {
		unset($input['created']);
	}

	if(is_null($input['comments'])) $input['comments'] = 0;

	Post::update($id, $input);

	Extend::process('post', $id);

	Notify::success(__('posts.updated', 'Your article has been updated.'));

	return Response::redirect('admin/posts/edit/' . $id);
}));

/*
	Add new post
*/
Route::get('admin/posts/add', array('before' => 'auth', 'do' => function() {
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

	return View::make('posts/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/posts/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('html')
		->is_max(3, __('posts.missing_html', 'Please enter your html'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/add');
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	if(empty($input['created'])) {
		$input['created'] = date('c');
	}

	$input['author'] = Auth::user()->id;

	if(is_null($input['comments'])) $input['comments'] = 0;

	$id = Post::create($input);

	Extend::process('post', $id);

	Notify::success(sprintf(__('posts.created', 'Your new article was created, <a href="%s">continue editing</a>.'), url('posts/edit/' . $id)));

	return Response::redirect('admin/posts');
}));

/*
	Preview
*/
Route::post('admin/posts/preview', array('before' => 'auth', 'do' => function() {
	$html = Input::get('html');

	// apply markdown processing
	$md = new Markdown;
	$output = Json::encode(array('html' => $md->transform($html)));

	return Response::make($output, 200, array('Content-Type' => 'application/json'));
}));

/*
	Delete
*/
Route::get('admin/posts/delete/(:num)', array('before' => 'auth', 'do' => function($id) {
	Post::find($id)->delete();

	Comment::where('post', '=', $id)->delete();

	Query::table('post_meta')->where('post', '=', $id)->delete();

	Notify::success(__('posts.post_success_deleted'));

	return Response::redirect('admin/posts');
}));