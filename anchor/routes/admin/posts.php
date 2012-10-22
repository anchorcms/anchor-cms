<?php

/*
	List all posts and paginate through them
*/
Route::get(array('admin/posts', 'admin/posts/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['posts'] = Post::paginate($page);

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
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived'),
		'published' => __('posts.published', 'Published')
	);

	$vars['templates'] = array('article' => 'Article');
	$vars['categories'] = Category::all();

	return View::make('posts/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/posts/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('title', 'slug', 'description', 'created',
		'html', 'category', 'status', 'comments', 'template'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('description')
		->is_max(3, __('posts.missing_description', 'Please enter a description'));

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

	if(empty($input['created'])) {
		$input['created'] = date('c');
	}

	if(is_null($input['comments'])) $input['comments'] = 0;

	Post::update($id, $input);

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
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived'),
		'published' => __('posts.published', 'Published')
	);

	$vars['templates'] = array('article' => 'Article');
	$vars['categories'] = Category::all();

	return View::make('posts/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/posts/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('title', 'slug', 'description', 'created',
		'html', 'category', 'status', 'comments', 'template'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('description')
		->is_max(3, __('posts.missing_description', 'Please enter a description'));

	$validator->check('html')
		->is_max(3, __('posts.missing_html', 'Please enter your html'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/add');
	}

	// process extend fields
	$postmeta = Extend::process();

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

	foreach($postmeta as $item) {
		Query::insert('postmeta')->insert(array(
			'post' => $id,
			'extend' => $item['extend'],
			'data' => $item['data']
		));
	}

	Notify::success(sprintf(__('posts.created', 'Your new article was created, <a href="%s">continue editing</a>.'), url('posts/edit/' . $id)));

	return Response::redirect('admin/posts');
}));
