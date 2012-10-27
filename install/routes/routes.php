<?php

/*
	Start (Language Select)
*/
Route::get(array('/', 'start'), function() {
	$vars['messages'] = Notify::read();
	$vars['languages'] = array();

	// search installed languages
	$path = PATH . 'anchor/language';
	$if = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

	foreach($if as $file) {
		if($file->isDir()) $vars['languages'][] = $file->getBasename();
	}
	
	//  only show if there's a choice
	if(count($vars['languages']) < 2) {
	    Session::put('install', array('language' => $vars['languages'][0]));
    	return Response::redirect('database');
	}

	return View::make('start', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('start', function() {
	$language = Input::get('language');

	$validator = new Validator(array('language' => $language));

	$validator->check('language')
		->is_max(2, 'Please select a language');

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('start');
	}

	Session::put('install', array('language' => $language));

	return Response::redirect('database');
});

/*
	MySQL Database
*/
Route::get('database', function() {
	$vars['messages'] = Notify::read();
	$vars['collations'] = array(
		'utf8_bin' => 'Unicode (multilingual), Binary',
		'utf8_czech_ci' => 'Czech, case-insensitive',
		'utf8_danish_ci' => 'Danish, case-insensitive',
		'utf8_esperanto_ci' => 'Esperanto, case-insensitive',
		'utf8_estonian_ci' => 'Estonian, case-insensitive',
		'utf8_general_ci' => 'Unicode (multilingual), case-insensitive',
		'utf8_hungarian_ci' => 'Hungarian, case-insensitive',
		'utf8_icelandic_ci' => 'Icelandic, case-insensitive',
		'utf8_latvian_ci' => 'Latvian, case-insensitive',
		'utf8_lithuanian_ci' => 'Lithuanian, case-insensitive',
		'utf8_persian_ci' => 'Persian, case-insensitive',
		'utf8_polish_ci' => 'Polish, case-insensitive',
		'utf8_roman_ci' => 'West European, case-insensitive',
		'utf8_romanian_ci' => 'Romanian, case-insensitive',
		'utf8_slovak_ci' => 'Slovak, case-insensitive',
		'utf8_slovenian_ci' => 'Slovenian, case-insensitive',
		'utf8_spanish2_ci' => 'Traditional Spanish, case-insensitive',
		'utf8_spanish_ci' => 'Spanish, case-insensitive',
		'utf8_swedish_ci' => 'Swedish, case-insensitive',
		'utf8_turkish_ci' => 'Turkish, case-insensitive',
		'utf8_unicode_ci' => 'Unicode (multilingual), case-insensitive'
	);

	return View::make('database', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('database', function() {
	$database = Input::get_array(array('host', 'port', 'user', 'pass', 'name', 'collation'));

	// test connection
	try {
		$connection = DB::connect(array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'charset' => 'utf8'
		));

		$connection->query('SET NAMES `utf8` COLLATE `' . $database['collation'] . '`');
	}
	catch(PDOException $e) {
		Input::flash();

		Notify::error($e->getMessage());

		return Response::redirect('database');
	}

	$settings = Session::get('install');

	$settings['database'] = $database;

	Session::put('install', $settings);

	return Response::redirect('metadata');
});

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

/*
	Account
*/
Route::get('account', function() {
	$vars['messages'] = Notify::read();

	return View::make('account', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('account', function() {
	$account = Input::get_array(array('username', 'email', 'password'));

	$validator = new Validator($account);

	$validator->check('username')
		->is_max(4, 'Please enter a username');

	$validator->check('email')
		->is_email('Please enter a valid email address');

	$validator->check('password')
		->is_max(6, 'Please enter a password');

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
		Installer::run($settings);
	}
	catch(Exception $e) {
		Input::flash();

		Notify::error($e->getMessage());

		return Response::redirect('account');
	}

	return Response::redirect('complete');
});

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