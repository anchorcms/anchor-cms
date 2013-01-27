<?php

Route::get(array('admin/extend/fields', 'admin/extend/fields/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['extend'] = Extend::paginate($page, Config::get('meta.posts_per_page'));

	return View::make('extend/fields/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

/*
	Add
*/
Route::get('admin/extend/fields/add', array('before' => 'auth', 'do' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::make('extend/fields/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/extend/fields/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('type', 'field', 'key', 'label', 'attributes'));

	if(empty($input['key'])) {
		$input['key'] = $input['label'];
	}

	$input['key'] = Str::slug($input['key']);

	$validator = new Validator($input);

	$validator->add('valid_key', function($str) {
		return Extend::where('key', '=', $str)->count() == 0;
	});

	$validator->check('key')
		->is_valid_key(__('extend.missing_key', 'Please enter a unique key'));

	$validator->check('label')
		->is_max(1, __('extend.missing_label', 'Please enter a label'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/extend/add');
	}

	if($input['field'] == 'image') {
		$attributes = Json::encode($input['attributes']);
	}
	else if($input['field'] == 'file') {
		$attributes = Json::encode(array(
			'attributes' => array(
				'type' => $input['attributes']['type']
			)
		));
	}
	else {
		$attributes = '';
	}

	Extend::create(array(
		'type' => $input['type'],
		'field' => $input['field'],
		'key' => $input['key'],
		'label' => $input['label'],
		'attributes' => $attributes
	));

	Notify::success(__('extend.extend_success_created', 'Field Created'));

	return Response::redirect('admin/extend/fields');
}));

/*
	Edit
*/
Route::get('admin/extend/fields/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	$extend = Extend::find($id);

	if($extend->attributes) {
		$extend->attributes = Json::decode($extend->attributes);
	}

	$vars['field'] = $extend;

	return View::make('extend/fields/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/extend/fields/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('type', 'field', 'key', 'label', 'attributes'));

	if(empty($input['key'])) {
		$input['key'] = $input['label'];
	}

	$input['key'] = Str::slug($input['key']);

	$validator = new Validator($input);

	$validator->add('valid_key', function($str) use($id) {
		return Extend::where('key', '=', $str)->where('id', '<>', $id)->count() == 0;
	});

	$validator->check('key')
		->is_valid_key(__('extend.missing_key', 'Please enter a unique key'));

	$validator->check('label')
		->is_max(1, __('extend.missing_label', 'Please enter a label'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/extend/add');
	}

	if($input['field'] == 'image') {
		$attributes = Json::encode($input['attributes']);
	}
	else if($input['field'] == 'file') {
		$attributes = Json::encode(array(
			'attributes' => array(
				'type' => $input['attributes']['type']
			)
		));
	}
	else {
		$attributes = '';
	}

	Extend::update($id, array(
		'type' => $input['type'],
		'field' => $input['field'],
		'key' => $input['key'],
		'label' => $input['label'],
		'attributes' => $attributes
	));

	Notify::success(__('extend.extend_success_updated', 'Field Updated'));

	return Response::redirect('admin/extend/fields/edit/' . $id);
}));

/*
	Delete
*/
Route::get('admin/extend/fields/delete/(:num)', array('before' => 'auth', 'do' => function($id) {
	$field = Extend::find($id);

	Query::table($field->type . '_meta')->where('extend', '=', $field->id)->delete();

	$field->delete();

	return Response::redirect('admin/extend/fields');
}));