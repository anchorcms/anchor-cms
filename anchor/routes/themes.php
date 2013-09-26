<?php

Route::collection(array('before' => 'auth'), function() {

	/*
		List all themes
	*/
	Route::get('admin/extend/themes', function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		
		$vars['themes'] = Themes::all();

		return View::create('extend/themes/index', $vars)
    		->partial('nav', 'extend/nav')
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		theme overview
	*/
	Route::get('admin/extend/themes/(:any)', function($slug) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('extend/themes/overview', $vars)
    		->partial('nav', 'extend/nav')
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		theme install
	*/
	Route::get('admin/extend/themes/(:any)/install', function($slug) {
		return Response::redirect('admin/extend/themes');
	});

	/*
		theme uninstall
	*/
	Route::get('admin/extend/themes/(:any)/uninstall', function($slug) {
		return Response::redirect('admin/extend/themes');
	});

});