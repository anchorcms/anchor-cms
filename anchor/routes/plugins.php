<?php

Route::collection(array('before' => 'auth'), function() {

	/*
		List all plugins
	*/
	Route::get('admin/extend/plugins', function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['plugins'] = Plugin::available();

		return View::create('extend/plugins/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Plugin overview
	*/
	Route::get('admin/extend/plugins/(:any)', function($slug) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['info'] = Plugin::about($slug);
		$vars['plugin'] = Plugin::where('path', '=', $slug)->fetch();

		$action = ($vars['plugin']) ? '/uninstall' : '/install';
		$vars['url'] = 'admin/extend/plugins/' . $vars['info']['path'] . '/' . $action;

		return View::create('extend/plugins/overview', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Plugin install
	*/
	Route::get('admin/extend/plugins/(:any)/install', function($slug) {
		$about = Plugin::about($slug);

		$plugin = Plugin::create($about);

		// run the plugin installer
		$plugin->instance()->install();

		Notify::success(__('plugins.installed', $plugin->name));

		return Response::redirect('admin/extend/plugins');
	});

	/*
		Plugin uninstall
	*/
	Route::get('admin/extend/plugins/(:any)/uninstall', function($slug) {
		$plugin = Plugin::where('path', '=', $slug)->fetch();

		// run the plugin uninstaller
		$plugin->instance()->uninstall();

		Notify::notice(__('plugins.uninstalled', $plugin->name));

		// remove from the database
		$plugin->delete();

		return Response::redirect('admin/extend/plugins');
	});

});