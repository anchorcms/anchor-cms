<?php

/*
	Complete
*/
Route::get('complete', function() {
	$settings = Session::get('install');

	return View::make('complete', $settings)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});