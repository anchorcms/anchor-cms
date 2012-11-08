<?php

/*
	Start (Language Select)
*/
Route::get(array('/', 'start'), array('before' => 'check', 'do' => function() {
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
}));

Route::post('start', array('before' => 'check', 'do' => function() {
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
}));