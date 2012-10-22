<?php

Route::get('admin', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
	return Response::redirect('admin/posts');
});

/*
	Log in
*/
Route::get('admin/login', function() {
	
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::make('login', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('admin/login', array('before' => 'csrf', 'do' => function() {
	if( ! Auth::attempt(Input::get('user'), Input::get('pass'))) {
		Notify::error(array('Invalid details'));

		return Response::redirect('admin/login');
	}

	return Response::redirect('admin/posts');
}));

/*
	Log out
*/
Route::get('admin/logout', function() {
	Auth::logout();
	return Response::redirect('admin/login');
});

/*
	Amnesia
*/
Route::get('admin/amnesia', function() {});

Route::post('admin/amnesia', function() {});

/*
	Reset password
*/
Route::get('admin/reset/(:any)', function($key) {});

Route::post('admin/reset/(:any)', function($key) {});