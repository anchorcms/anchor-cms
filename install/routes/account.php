<?php

/*
	Account
*/
Route::get('account', array('before' => 'check', 'do' => function() {
	// check we have a database
	if( ! Session::get('install.metadata')) {
		Notify::error('Please enter your site details');

		return Response::redirect('metadata');
	}

	$vars['messages'] = Notify::read();

	return View::make('account', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('account', array('before' => 'check', 'do' => function() {
	$account = Input::get_array(array('username', 'email', 'password'));

	$validator = new Validator($account);

	$validator->check('username')
		->is_max(4, 'Please enter a username');

	$validator->check('email')
		->is_email('Please enter a valid email address');

	$validator->check('password')
		->is_max(6, 'Please enter a password, at least 6 characters long');

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('account');
	}

	$settings = Session::get('install');

	$settings['account'] = $account;

	Session::put('install', $settings);

	// run install process
	try {
		Installer::run();
	}
	catch(Exception $e) {
		Input::flash();

		Notify::error($e->getMessage());

		return Response::redirect('account');
	}

	return Response::redirect('complete');
}));