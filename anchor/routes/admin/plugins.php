<?php

Route::get('admin/extend/plugins', array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::make('extend/plugins/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));