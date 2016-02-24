<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List Categories
	*/
	Route::get(array('admin/categories', 'admin/categories/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['categories'] = Category::paginate($page, Config::get('admin.posts_per_page'));

		return View::create('categories/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Category
	*/
	Route::get('admin/categories/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['category'] = Category::find($id);

		// extended fields
		$vars['fields'] = Extend::fields('category', $id);

		return View::create('categories/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/categories/edit/(:num)', function($id) {
		$input = Input::get(array('title', 'slug', 'description'));
		
		foreach($input as $key => &$value) {
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->check('title')
			->is_max(3, __('categories.title_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/categories/edit/' . $id);
		}

		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		$input['slug'] = slug($input['slug']);

		Category::update($id, $input);
		Extend::process('category', $id);

		Notify::success(__('categories.updated'));

		return Response::redirect('admin/categories/edit/' . $id);
	});

	/*
		Add Category
	*/
	Route::get('admin/categories/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		// extended fields
		$vars['fields'] = Extend::fields('category');

		return View::create('categories/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/categories/add', function() {
		$input = Input::get(array('title', 'slug', 'description'));
		
		foreach($input as $key => &$value) {
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->check('title')
			->is_max(3, __('categories.title_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/categories/add');
		}

		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		$input['slug'] = slug($input['slug']);

		$category = Category::create($input);
		Extend::process('category', $category->id);

		Notify::success(__('categories.created'));

		return Response::redirect('admin/categories');
	});

	/*
		Delete Category
	*/
	Route::get('admin/categories/delete/(:num)', function($id) {
		$total = Category::count();

		if($total == 1) {
			Notify::error(__('categories.delete_error'));

			return Response::redirect('admin/categories/edit/' . $id);
		}

		// move posts
		$category = Category::where('id', '<>', $id)->fetch();

		// delete selected
		Category::find($id)->delete();

		// update posts
		Post::where('category', '=', $id)->update(array(
			'category' => $category->id
		));

		Notify::success(__('categories.deleted'));

		return Response::redirect('admin/categories');
	});

});
