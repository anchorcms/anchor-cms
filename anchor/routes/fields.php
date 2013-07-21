<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Fields
	*/
	Route::get(array('admin/extend/fields', 'admin/extend/fields/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$perpage = Config::meta('admin_posts_per_page', 6);
		$vars['extend'] = Extend::paginate($page, $perpage);

		return View::create('extend/fields/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Add Field
	*/
	Route::get('admin/extend/fields/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('extend/fields/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/extend/fields/add', function() {
		$input = Input::get(array('data_type', 'field_type', 'key', 'label', 'attributes'));

		if($errors = Extend::validate($input)) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/extend/fields/add');
		}

		Extend::create($input);

		Notify::success(__('extend.field_created'));

		return Response::redirect('admin/extend/fields');
	});

	/*
		Edit Field
	*/
	Route::get('admin/extend/fields/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$extend = Extend::find($id);

		if($extend->attributes) {
			$extend->attributes = Json::decode($extend->attributes);
		}

		$vars['field'] = $extend;

		return View::create('extend/fields/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/extend/fields/edit/(:num)', function($id) {
		$input = Input::get(array('data_type', 'field_type', 'key', 'label', 'attributes'));

		if($errors = Extend::validate($input, $id)) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/extend/fields/add');
		}

		Extend::update($id, $input);

		Notify::success(__('extend.field_updated'));

		return Response::redirect('admin/extend/fields/edit/' . $id);
	});

	/*
		Delete Field
	*/
	Route::get('admin/extend/fields/delete/(:num)', function($id) {
		$field = Extend::find($id);

		Query::table($field->data_type . '_meta')->where('extend', '=', $field->id)->delete();

		$field->delete();

		Notify::success(__('extend.field_deleted'));

		return Response::redirect('admin/extend/fields');
	});

});
