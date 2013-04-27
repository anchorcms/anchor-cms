<?php

Route::collection(array('before' => 'auth'), function() {

	/*
		List Metadata
	*/
	Route::get('admin/extend/metadata', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['meta'] = Config::get('meta');
		$vars['pages'] = Page::dropdown();
		$vars['themes'] = Themes::all();

		return View::create('extend/metadata/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Update Metadata
	*/
	Route::post('admin/extend/metadata', function() {
		$input = Input::get(array('sitename', 'description', 'home_page', 'posts_page',
			'posts_per_page', 'auto_published_comments', 'theme', 'comment_notifications', 'comment_moderation_keys'));

		$validator = new Validator($input);

		$validator->check('sitename')
			->is_max(3, __('metadata.sitename_missing'));

		$validator->check('description')
			->is_max(3, __('metadata.sitedescription_missing'));

		$validator->check('posts_per_page')
			->is_regex('#^[0-9]+$#', __('metadata.missing_posts_per_page', 'Please enter a number for posts per page'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/extend/metadata');
		}

		// convert double quotes so we dont break html
		$input['sitename'] = htmlspecialchars($input['sitename'], ENT_COMPAT, Config::app('encoding'), false);
		$input['description'] = htmlspecialchars($input['description'], ENT_COMPAT, Config::app('encoding'), false);

		foreach($input as $key => $value) {
			Query::table(Base::table('meta'))->where('key', '=', $key)->update(array('value' => $value));
		}

		Notify::success(__('metadata.updated'));

		return Response::redirect('admin/extend/metadata');
	});

});