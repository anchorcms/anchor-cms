<?php

Route::collection(array('before' => 'auth,install_exists'), function() {

	/*
		List Menu Items
	*/
	Route::get('admin/menu', function() {
		$vars['messages'] = Notify::read();
		$vars['pages'] = Page::where('show_in_menu', '=', 1)->sort('menu_order')->get();

		return View::create('menu/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Update order
	*/
	Route::post('admin/menu/update', function() {
		$sort = Input::get('sort');

		foreach($sort as $index => $id) {
			Page::where('id', '=', $id)->update(array('menu_order' => $index));
		}

		return Response::json(array('result' => true));
	});

});
