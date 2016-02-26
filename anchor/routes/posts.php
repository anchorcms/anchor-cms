<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List all posts and paginate through them
	*/
	Route::get(array('admin/posts', 'admin/posts/(:num)'), function($page = 1) {
		$perpage = Config::get('admin.posts_per_page');
		$total = Post::count();
		$posts = Post::sort('created', 'desc')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/posts');

		$pagination = new Paginator($posts, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['posts'] = $pagination;
		$vars['categories'] = Category::sort('title')->get();
		$vars['status'] = 'all';

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
		$perpage = Config::get('admin.posts_per_page');
		$total = $query->count();
		$posts = $query->sort('created', 'desc')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/posts/category/' . $category->slug);

		$pagination = new Paginator($posts, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['posts'] = $pagination;
		$vars['category'] = $category;
		$vars['categories'] = Category::sort('title')->get();
		$vars['status']	= 'all';

		return View::create('posts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List posts by status and paginate through them
	*/
	Route::get(array(
		'admin/posts/status/(:any)',
		'admin/posts/status/(:any)/(:num)'), function($status, $post = 1) {

		$query = Post::where('status', '=', $status);

		$perpage = Config::get('admin.posts_per_page');
		$total = $query->count();
		$posts = $query->sort('title')->take($perpage)->skip(($post - 1) * $perpage)->get();
		$url = Uri::to('admin/posts/status');

		$pagination = new Paginator($posts, $total, $post, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['posts'] = $pagination;
		$vars['status'] = $status;
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
		$input = Input::get(array('title', 'slug', 'description', 'created',
			'markdown', 'css', 'js', 'category', 'status', 'comments'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);
		
		// an array of items that we shouldn't encode - they're no XSS threat
		$dont_encode = array('description', 'markdown', 'css', 'js');
		
		foreach($input as $key => &$value) {
			if(in_array($key, $dont_encode)) continue;
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->add('duplicate', function($str) use($id) {
			return Post::where('slug', '=', $str)->where('id', '<>', $id)->count() == 0;
		});

		$validator->check('title')
			->is_max(3, __('posts.title_missing'));

		$validator->check('slug')
			->is_max(3, __('posts.slug_missing'))
			->is_duplicate(__('posts.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('posts.slug_invalid'));

		$validator->check('created')
			->is_regex('#^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2}$#', __('posts.time_invalid'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/posts/edit/' . $id);
		}

		$current_post = Post::find($id);
		if($current_post->status == 'draft') {
			$input['created'] = Date::mysql('now');
		} else {
			unset($input['created']);
		}

		if(empty($input['comments'])) {
			$input['comments'] = 0;
		}

		if(empty($input['markdown'])) {
			$input['status'] = 'draft';
		}

		$input['html'] = parse($input['markdown']);

		Post::update($id, $input);

		Extend::process('post', $id);

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
		$input = Input::get(array('title', 'slug', 'description', 'created',
			'markdown', 'css', 'js', 'category', 'status', 'comments'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);
		
		// an array of items that we shouldn't encode - they're no XSS threat
		$dont_encode = array('description', 'markdown', 'css', 'js');
		
		foreach($input as $key => &$value) {
			if(in_array($key, $dont_encode)) continue;
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->add('duplicate', function($str) {
			return Post::where('slug', '=', $str)->count() == 0;
		});

		$validator->check('title')
			->is_max(3, __('posts.title_missing'));

		$validator->check('slug')
			->is_max(3, __('posts.slug_missing'))
			->is_duplicate(__('posts.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('posts.slug_invalid'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/posts/add');
		}

		if(empty($input['created'])) {
			$input['created'] = Date::mysql('now');
		}

		$user = Auth::user();

		$input['author'] = $user->id;

		if(empty($input['comments'])) {
			$input['comments'] = 0;
		}

		if(empty($input['markdown'])) {
			$input['status'] = 'draft';
		}

		$input['html'] = parse($input['markdown']);

		$post = Post::create($input);

		Extend::process('post', $post->id);

		Notify::success(__('posts.created'));
		
		if(Input::get('autosave') === 'true') return Response::redirect('admin/posts/edit/' . $page->id);
		else return Response::redirect('admin/posts');
	});

	/*
		Preview post
	*/
	Route::post('admin/posts/preview', function() {
		$markdown = Input::get('markdown');

		// apply markdown processing
		$md = new Markdown;
		$output = Json::encode(array('markdown' => $md->transform($markdown)));

		return Response::create($output, 200, array('content-type' => 'application/json'));
	});

	/*
		Delete post
	*/
	Route::get('admin/posts/delete/(:num)', function($id) {
		Post::find($id)->delete();

		Comment::where('post', '=', $id)->delete();

		Query::table(Base::table('post_meta'))->where('post', '=', $id)->delete();

		Notify::success(__('posts.deleted'));

		return Response::redirect('admin/posts');
	});
});
