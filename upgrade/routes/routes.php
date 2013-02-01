<?php

Route::get(array('/', 'upgrade'), function() {
	return View::make('start')
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::get('start', function() {
	// backup
	Upgrader::import();

	// backup
	Upgrader::backup();

	// download latest anchor
	//Upgrader::download();

	// deploy new files
	//Upgrader::deploy(PATH);

	// run database changes
	Upgrader::database();

	// write database config file
	Upgrader::config_database();

	// write application config file
	Upgrader::config_application();

	return Response::redirect('complete');
});

Route::get('complete', function() {
	$vars['root_uri'] = $GLOBALS['ANCHOR_URL'];

	return View::make('complete', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});