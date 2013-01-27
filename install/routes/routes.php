<?php

/*
	Filters
*/
Route::filter('check', function() {
	if(file_exists(PATH . 'anchor/config/database.php')) {
		$root = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';

		return Response::make('', 302, array('location' => $root));
	}
});

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