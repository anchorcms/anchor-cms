<?php

/*
	Edit/Index
*/

Route::get('admin/extend/metadata', array('before' => 'auth', 'do' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['meta'] = Config::get('meta');
	$vars['pages'] = Page::dropdown();
	$vars['themes'] = Themes::all();

	return View::make('extend/metadata/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/extend/metadata', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('sitename', 'description', 'home_page', 'posts_page',
		'posts_per_page', 'auto_published_comments', 'theme', 'comment_notifications', 'comment_moderation_keys', 'twitter'));

	$validator = new Validator($input);

	$validator->check('sitename')
		->is_max(3, __('metadata.missing_sitename'));

	$validator->check('description')
		->is_max(3, __('metadata.missing_description'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/extend/metadata');
	}

	foreach($input as $key => $value) {
		Query::table('meta')->where('key', '=', $key)->update(array('value' => $value));
	}

	Notify::success(__('metadata.meta_success_updated'));

	return Response::redirect('admin/extend/metadata');
}));