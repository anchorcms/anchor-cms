<?php

Route::get(array('/', 'upgrade'), function() {
	return View::make('start')
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('start', function() {
	// backup
	Upgrader::import();

	// backup
	Upgrader::backup();

	// download latest anchor
	Upgrader::download();

	// deploy new files
	Upgrader::deploy(PATH);

	// run database changes
	Upgrade::database();

	// write database config file
	Upgrade::config_database();

	// write application config file
	Upgrade::config_application();

	return Response::redirect('complete');
});

Route::get('complete', function() {
	return View::make('complete')
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

/*
	404 catch all
*/
Route::any('*', function() {
	return Response::error(404);
});