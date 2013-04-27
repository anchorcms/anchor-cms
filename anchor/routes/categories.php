<?php

Route::collection(array('before' => 'auth'), function() {

	/*
		List Categories
	*/
	Route::get(array('admin/categories', 'admin/categories/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['categories'] = Category::paginate($page, Config::get('meta.posts_per_page'));

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

		return View::create('categories/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/categories/edit/(:num)', function($id) {
		$input = Input::get(array('title', 'slug', 'description'));

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

		Notify::success(__('categories.updated'));

		return Response::redirect('admin/categories/edit/' . $id);
	});

	/*
		Add Category
	*/
	Route::get('admin/categories/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('categories/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/categories/add', function() {
		$input = Input::get(array('title', 'slug', 'description'));

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

		Category::create($input);

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
