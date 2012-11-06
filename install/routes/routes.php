<?php

/*
	Complete
*/
Route::get('complete', function() {
	$settings = Session::get('install', array());
	$settings['root_uri'] = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';

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