<?php

/*
	List all
*/
Route::get(array(
	'admin/comments', 
	'admin/comments/(pending|approved|spam)',
	'admin/comments/(pending|approved|spam)/(:num)',
	'admin/comments/(pending|approved|spam)/(:num)/(:num)'
), array('before' => 'auth', 'do' => function($status = 'all', $page = 1, $perpage = 10) {
	$vars['messages'] = Notify::read();

	$query = Query::table(Comment::$table);

	if(in_array($status, array('pending', 'approved', 'spam'))) {
		$query->where('status', '=', $status);
	}

	$count = $query->count();
	$results = $query->take($perpage)->skip(($page - 1) * $perpage)->order_by('date', 'desc')->get();

	$vars['comments'] = new Paginator($results, $count, $page, $perpage, url('comments/' . $status));

	return View::make('comments/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

/*
	Edit
*/
Route::get('admin/comments/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['comment'] = Comment::find($id);

	$vars['statuses'] = array(
		'approved' => __('comments.approved', 'Approved'), 
		'pending' => __('comments.pending', 'Pending'), 
		'spam' => __('comments.spam', 'Spam')
	);

	return View::make('comments/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/comments/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('name', 'email', 'text', 'status'));

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
	Delete
*/
Route::get('admin/comments/delete/(:num)', array('before' => 'auth', 'do' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->delete();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Approve
*/
Route::get('admin/comments/status/approve/(:num)', array('before' => 'auth', 'do' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'approved';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Unapprove
*/
Route::get('admin/comments/status/unapprove/(:num)', array('before' => 'auth', 'do' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Spam
*/
Route::get('admin/comments/status/spam/(:num)', array('before' => 'auth', 'do' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'spam';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Not Spam
*/
Route::get('admin/comments/status/notspam/(:num)', array('before' => 'auth', 'do' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));