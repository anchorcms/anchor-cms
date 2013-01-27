<?php

Route::filter('auth', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
});

Route::filter('csrf', function() {
	if( ! Csrf::check(Input::get('token'))) {
		Notify::error(array('Invalid token'));

		return Response::redirect('admin/login');
	}
});