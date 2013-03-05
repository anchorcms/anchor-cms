<?php

/*
	List Metadata
*/
Route::get('admin/extend/metadata', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	$vars['meta'] = Config::get('meta');
	$vars['pages'] = Page::dropdown();
	$vars['themes'] = Themes::all();

	return View::create('extend/metadata/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Update Metadata
*/
Route::post('admin/extend/metadata', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('sitename', 'description', 'home_page', 'posts_page',
		'posts_per_page', 'auto_published_comments', 'theme', 'comment_notifications', 'comment_moderation_keys'));

	$validator = new Validator($input);

	$validator->check('sitename')
		->is_max(3, __('metadata.missing_sitename'));

	$validator->check('description')
		->is_max(3, __('metadata.missing_description'));

	$validator->check('posts_per_page')
		->is_regex('#^[0-9]+$#', __('metadata.missing_posts_per_page', 'Please enter a number for posts per page'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/extend/metadata');
	}

	foreach($input as $key => $value) {
		Query::table(Base::table('meta'))->where('key', '=', $key)->update(array('value' => $value));
	}

	Notify::success(__('metadata.meta_success_updated'));

	return Response::redirect('admin/extend/metadata');
}));