<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	Route::get('admin/panel', function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('panel', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

});