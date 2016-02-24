<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List Comments
	*/
	Route::get(array('admin/comments', 'admin/comments/(:num)'), function($page = 1) {
		$query = Query::table(Base::table(Comment::$table));
		$perpage = Config::get('admin.posts_per_page');

		$count = $query->count();
		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

		$vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('admin/comments'));
		$vars['messages'] = Notify::read();

		$vars['statuses'] = array(
			array('url' => '', 'lang' => 'global.all', 'class' => 'active'),
			array('url' => 'pending', 'lang' => 'global.pending', 'class' => 'pending'),
			array('url' => 'approved', 'lang' => 'global.approved', 'class' => 'approved'),
			array('url' => 'spam', 'lang' => 'global.spam', 'class' => 'spam')
		);

		return View::create('comments/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List Comments by status
	*/
	Route::get(array(
		'admin/comments/(pending|approved|spam)',
		'admin/comments/(pending|approved|spam)/(:num)'), function($status = '', $page = 1) {

		$query = Query::table(Base::table(Comment::$table));
		$perpage = Config::get('admin.posts_per_page');

		if(in_array($status, array('pending', 'approved', 'spam'))) {
			$query->where('status', '=', $status);
		}

		$count = $query->count();
		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

		$vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('admin/comments/' . $status));
		$vars['messages'] = Notify::read();

		$vars['status'] = $status;
		$vars['statuses'] = array(
			array('url' => '', 'lang' => 'global.all', 'class' => ''),
			array('url' => 'pending', 'lang' => 'global.pending', 'class' => 'pending'),
			array('url' => 'approved', 'lang' => 'global.approved', 'class' => 'approved'),
			array('url' => 'spam', 'lang' => 'global.spam', 'class' => 'spam')
		);

		return View::create('comments/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Comment
	*/
	Route::get('admin/comments/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['comment'] = Comment::find($id);

		$vars['statuses'] = array(
			'approved' => __('global.approved'),
			'pending' => __('global.pending'),
			'spam' => __('global.spam')
		);

		return View::create('comments/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/comments/edit/(:num)', function($id) {
		$input = Input::get(array('name', 'email', 'text', 'status'));
		
		foreach($input as $key => &$value) {
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->check('name')
			->is_max(3, __('comments.name_missing'));

		$validator->check('text')
			->is_max(3, __('comments.text_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/comments/edit/' . $id);
		}

		Comment::update($id, $input);

		Notify::success(__('comments.updated'));

		return Response::redirect('admin/comments/' . $input['status']);
	});

	/*
		Delete Comment
	*/
	Route::get('admin/comments/delete/(:num)', function($id) {
		$comment = Comment::find($id);
		$status = $comment->status;

		$comment->delete();

		Notify::success(__('comments.deleted'));

		return Response::redirect('admin/comments/' . $status);
	});

});