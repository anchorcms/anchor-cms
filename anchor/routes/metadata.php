<?php

Route::collection(array('before' => 'auth,install_exists'), function() {

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
			'posts_per_page', 'auto_published_comments', 'theme', 'comment_notifications', 'comment_moderation_keys', 'show_all_posts'));
		
		foreach($input as $key => &$value) {
			$value = eq($value);
		}
		
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
		$input['sitename'] = e($input['sitename'], ENT_COMPAT);
		$input['description'] = e($input['description'], ENT_COMPAT);

		foreach($input as $key => $value) {
			$value = is_null($value) ? 0 : $value;
			Query::table(Base::table('meta'))->where('key', '=', $key)->update(array('value' => $value));
		}

		Notify::success(__('metadata.updated'));

		return Response::redirect('admin/extend/metadata');
	});

});
