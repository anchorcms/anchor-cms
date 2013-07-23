<?php

Route::action('check', function() {
	if($errors = Support::run_checks()) {
		Session::put('errors', $errors);

		return Response::redirect('woops');
	}
});

/*
	Requirements
*/
Route::get('woops', function() {
	$vars['errors'] = Session::get('errors');
	Session::erase('errors');

	if(empty($vars['errors'])) return Response::redirect('start');

	return Layout::create('halt', $vars);
});

/*
	Start (Language Select)
*/
Route::get(array('/', 'start'), array('before' => 'check', 'main' => function() {
	$vars['messages'] = Notify::read();

	$vars['languages'] = Support::languages();
	$vars['prefered_language'] = Support::prefered_language();
	$vars['timezones'] = Support::timezones();
	$vars['prefered_timezone'] = Support::prefered_timezone();

	return Layout::create('start', $vars);
}));

Route::post('start', array('before' => 'check', 'main' => function() {
	$i18n = Input::get(array('language', 'timezone'));

	$validator = new Validator($i18n);

	$validator->check('language')
		->is_max(2, 'Please select a language');

	$validator->check('timezone')
		->is_max(2, 'Please select a timezone');

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('start');
	}

	Session::put('install.i18n', $i18n);

	return Response::redirect('database');
}));

/*
	MySQL Database
*/
Route::get('database', array('before' => 'check', 'main' => function() {
	// check we have a selected language
	if( ! Session::get('install.i18n')) {
		Notify::error('Please select a language');

		return Response::redirect('start');
	}

	$vars['messages'] = Notify::read();
	$vars['collations'] = array(
		'utf8_bin' => 'Unicode (multilingual), Binary',
		'utf8_general_ci' => 'Unicode (multilingual), case-insensitive',
		'utf8_unicode_ci' => 'Unicode (multilingual), case-insensitive'
	);

	return Layout::create('database', $vars);
}));

Route::post('database', array('before' => 'check', 'main' => function() {
	$database = Input::get(array('host', 'port', 'user', 'pass', 'name', 'collation', 'prefix'));

	// test connection
	try {
		$connection = DB::factory(array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'port' => $database['port'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'charset' => 'utf8',
			'prefix' => $database['prefix']
		));
	}
	catch(Exception $e) {
		Input::flash();

		Notify::error($e->getMessage());

		return Response::redirect('database');
	}

	Session::put('install.database', $database);

	return Response::redirect('metadata');
}));

/*
	Metadata
*/
Route::get('metadata', array('before' => 'check', 'main' => function() {
	// check we have a database
	if( ! Session::get('install.database')) {
		Notify::error('Please enter your database details');

		return Response::redirect('database');
	}

	$vars['messages'] = Notify::read();
	$vars['site_path'] = dirname(dirname($_SERVER['SCRIPT_NAME']));
	$vars['themes'] = Themes::all();
	$vars['support'] = new Support;

	return Layout::create('metadata', $vars);
}));

Route::post('metadata', array('before' => 'check', 'main' => function() {
	$metadata = Input::get(array('site_name', 'site_description', 'site_path', 'theme', 'rewrite'));

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

	Session::put('install.metadata', $metadata);

	return Response::redirect('account');
}));

/*
	Account
*/
Route::get('account', array('before' => 'check', 'main' => function() {
	// check we have a database
	if( ! Session::get('install.metadata')) {
		Notify::error('Please enter your site details');

		return Response::redirect('metadata');
	}

	$vars['messages'] = Notify::read();

	return Layout::create('account', $vars);
}));

Route::post('account', array('before' => 'check', 'main' => function() {
	$account = Input::get(array('username', 'email', 'password'));

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

	Session::put('install.account', $account);

	// run install process
	try {
		$installer = new Installer;
		$installer->run();
	}
	catch(Exception $e) {
		Input::flash();

		Notify::error($e->getMessage());

		return Response::redirect('account');
	}

	return Response::redirect('complete');
}));

/*
	Complete
*/
Route::get('complete', function() {
	// check we have a database
	if( ! Session::get('install')) {
		Notify::error('Please select your language');

		return Response::redirect('start');
	}

	$settings = Session::get('install');
	$vars['site_uri'] = $settings['metadata']['site_path'];
	$vars['admin_uri'] = rtrim($settings['metadata']['site_path'], '/') . '/index.php/admin/login';
	$vars['htaccess'] = Session::get('htaccess');

	// scrub session now we are done
	Session::erase('install');

	return Layout::create('complete', $vars);
});

/*
	404 catch all
*/
Route::not_found(function() {
	return Response::error(404);
});