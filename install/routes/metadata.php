<?php

/*
	Metadata
*/
Route::get('metadata', array('before' => 'check', 'do' => function() {
	// check we have a database
	if( ! Session::get('install.database')) {
		Notify::error('Please select a database');

		return Response::redirect('database');
	}

	$vars['messages'] = Notify::read();
	$vars['path'] = $GLOBALS['ANCHOR_URL'];

	return View::make('metadata', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('metadata', array('before' => 'check', 'do' => function() {
	$metadata = Input::get_array(array('site_name', 'site_description', 'site_path', 'theme'));

	$validator = new Validator($metadata);

	$validator->check('site_name')
		->is_max(4, 'Please enter a site name');

	$validator->check('site_description')
		->is_max(4, 'Please enter a site description');

	$validator->check('site_path')
		->is_max(1, 'Please enter a site path');

	$validator->check('theme')
		->is_max(1, 'Please select a site theme');

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('metadata');
	}

	$settings = Session::get('install');

	$settings['metadata'] = $metadata;

	Session::put('install', $settings);

	return Response::redirect('account');
}));