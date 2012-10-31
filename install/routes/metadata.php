<?php

/*
	Metadata
*/
Route::get('metadata', function() {
	$vars['messages'] = Notify::read();
	$vars['path'] = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';

	return View::make('metadata', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('metadata', function() {
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
});