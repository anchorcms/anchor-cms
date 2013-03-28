<?php

/*
	List all plugins
*/
Route::get('admin/extend/plugins', array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/plugins/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));