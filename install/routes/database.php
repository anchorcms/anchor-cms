<?php


/*
	MySQL Database
*/
Route::get('database', array('before' => 'check', 'do' => function() {
	// check we have a selected language
	if( ! Session::get('install.language')) {
		Notify::error('Please select a language');

		return Response::redirect('start');
	}

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
}));

Route::post('database', array('before' => 'check', 'do' => function() {
	$database = Input::get_array(array('host', 'port', 'user', 'pass', 'name', 'collation'));

	// test connection
	try {
		$connection = DB::connect(array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'port' => $database['port'],
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
}));