<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List Vars
	*/
	Route::get('admin/extend/pagetypes', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['pagetypes'] = Query::table(Base::table('pagetypes'))->sort('key')->get();

		return View::create('extend/pagetypes/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Add Var
	*/
	Route::get('admin/extend/pagetypes/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('extend/pagetypes/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/extend/pagetypes/add', function() {
		$input = Input::get(array('key', 'value'));
		
		$input['key'] = slug($input['key'], '_');
		
		$validator = new Validator($input);

		$validator->add('valid_key', function($str) {
			return Query::table(Base::table('pagetypes'))
			->where('key', '=', $str)
			->count() == 0;
		});

		$validator->check('key')
			->is_max(2, __('extend.key_missing'))
			->is_valid_key(__('extend.key_exists'));

		$validator->check('value')
			->is_max(1, __('extend.name_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/extend/pagetypes/add');
		}

		Query::table(Base::table('pagetypes'))->insert($input);

		Notify::success(__('extend.pagetype_created'));

		return Response::redirect('admin/extend/pagetypes');
	});

	/*
		Edit Var
	*/
	Route::get('admin/extend/pagetypes/edit/(:any)', function($key) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['pagetype'] = Query::table(Base::table('pagetypes'))->where('key', '=', $key)->fetch();

		return View::create('extend/pagetypes/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/extend/pagetypes/edit/(:any)', function($key) {
		$input = Input::get(array('key', 'value'));
		
		$input['key'] = slug($input['key'], '_');
		
		$validator = new Validator($input);

		$validator->add('valid_key', function($str) use($key) {
			// no change
			if($str == $key) return true;
			// check the new key $str is available
			return Query::table(Base::table('pagetypes'))->where('key', '=', $str)->count() == 0;
		});

		$validator->check('key')
			->is_max(2, __('extend.key_missing'))
			->is_valid_key(__('extend.key_exists'));

		$validator->check('value')
			->is_max(1, __('extend.name_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/extend/pagetypes/edit/' . $key);
		}

		Query::table(Base::table('pagetypes'))->where('key', '=', $key)->update($input);

		Notify::success(__('extend.pagetype_updated'));

		return Response::redirect('admin/extend/pagetypes');
	});

	/*
		Delete Var
	*/
	Route::get('admin/extend/pagetypes/delete/(:any)', function($key) {
		Query::table(Base::table('pagetypes'))->where('key', '=', $key)->delete();

		Notify::success(__('extend.pagetype_deleted'));

		return Response::redirect('admin/extend/pagetypes');
	});

});