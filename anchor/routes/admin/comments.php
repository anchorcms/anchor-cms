<?php


/*
	List Comments
*/
Route::get(array(
	'admin/comments',
	'admin/comments/(pending|approved|spam)',
	'admin/comments/(pending|approved|spam)/(:num)',
	'admin/comments/(pending|approved|spam)/(:num)/(:num)'
), array('before' => 'auth', 'main' => function($status = 'all', $page = 1, $perpage = 10) {
	$vars['messages'] = Notify::read();

	$query = Query::table(Base::table(Comment::$table));

	if(in_array($status, array('pending', 'approved', 'spam'))) {
		$query->where('status', '=', $status);
	}

	$count = $query->count();
	$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

	$vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('comments/' . $status));

	return View::create('comments/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit Comment
*/
Route::get('admin/comments/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['comment'] = Comment::find($id);

	$vars['statuses'] = array(
		'approved' => __('comments.approved', 'Approved'),
		'pending' => __('comments.pending', 'Pending'),
		'spam' => __('comments.spam', 'Spam')
	);

	return View::create('comments/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/comments/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('name', 'email', 'text', 'status'));

	$validator = new Validator($input);

	$validator->check('name')
		->is_max(3, __('comments.missing_name', 'Please enter a name'));

	$validator->check('text')
		->is_max(3, __('comments.missing_text', 'Please enter comment text'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/comments/edit/' . $id);
	}

	Comment::update($id, $input);

	Notify::success(__('comments.updated', 'Your comment has been updated.'));

	return Response::redirect('admin/comments/' . $input['status']);
}));

/*
	Delete Comment
*/
Route::get('admin/comments/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->delete();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Approve Comment
*/
Route::get('admin/comments/status/approve/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'approved';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Unapprove Comment
*/
Route::get('admin/comments/status/unapprove/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Spam Comment
*/
Route::get('admin/comments/status/spam/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'spam';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Not Spam Comment
*/
Route::get('admin/comments/status/notspam/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));