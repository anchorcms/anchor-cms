<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List all posts and paginate through them
	*/
	Route::get(array('admin/posts', 'admin/posts/(:num)'), function($page = 1) {
		$perpage = Config::meta('admin_posts_per_page');
		$total = Post::count();
		$posts = Post::sort('created', 'desc')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/posts');
		$pagination = new Paginator($posts, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['posts'] = $pagination;
		$vars['categories'] = Category::sort('title')->get();

		return View::create('posts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List posts by category and paginate through them
	*/
	Route::get(array(
		'admin/posts/category/(:any)',
		'admin/posts/category/(:any)/(:num)'), function($slug, $page = 1) {

		if( ! $category = Category::slug($slug)) {
			return Response::error(404);
		}

		$query = Post::where('category', '=', $category->id);

		$perpage = Config::meta('posts_per_page');
		$total = $query->count();
		$posts = $query->sort('created', 'desc')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/posts/category/' . $category->slug);

		$pagination = new Paginator($posts, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['posts'] = $pagination;
		$vars['category'] = $category;
		$vars['categories'] = Category::sort('title')->get();

		return View::create('posts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit post
	*/
	Route::get('admin/posts/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['article'] = Post::find($id);
		$vars['page'] = Registry::get('posts_page');

		// extended fields
		$vars['fields'] = Extend::fields('post', $id);

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		$vars['categories'] = Category::dropdown();

		return View::create('posts/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/posts/edit/(:num)', function($id) {
		$input = Post::input();

		if($errors = Post::validate($input, $id)) {
			if(Request::ajax()) {
				return Response::json(array('result' => false, 'messages' => $errors));
			}

			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/posts/edit/' . $id);
		}

		Post::update($id, $input);

		if(Request::ajax()) {
			return Response::json(array('result' => true, 'messages' => array(__('posts.updated'))));
		}

		Notify::success(__('posts.updated'));

		return Response::redirect('admin/posts/edit/' . $id);
	});

	/*
		Add new post
	*/
	Route::get('admin/posts/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['page'] = Registry::get('posts_page');

		// extended fields
		$vars['fields'] = Extend::fields('post');

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		$vars['categories'] = Category::dropdown();

		return View::create('posts/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/posts/add', function() {
		$input = Post::input();

		if($errors = $errors = Post::validate($input)) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/posts/add');
		}

		Post::create($input);

		Notify::success(__('posts.created'));

		return Response::redirect('admin/posts');
	});

	/*
		Preview post
	*/
	Route::post('admin/posts/preview', function() {
		$html = Input::get('html');

		$output = Json::encode(array(
			'html' => Markdown::defaultTransform($html)
		));

		return Response::create($output, 200, array('content-type' => 'application/json'));
	});

	/*
		Delete post
	*/
	Route::get('admin/posts/delete/(:num)', function($id) {
		if($post = Post::find($id)) {
			$post->delete();
			Notify::success(__('posts.deleted'));
		}

		return Response::redirect('admin/posts');
	});

	/*
		Upload a image
	*/
	Route::post('admin/posts/upload', function() {
		$uploader = new Uploader(PATH . 'content', array('png', 'jpg', 'bmp', 'gif'));
		$filepath = $uploader->upload($_FILES['file']);

		$uri = Config::app('url', '/') . 'content/' . basename($filepath);
		$output = array('uri' => $uri);

		return Response::json($output);
	});

});
