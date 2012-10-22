<?php

/*
	List all
*/
Route::get(array('admin/categories', 'admin/categories/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['categories'] = Category::paginate($page);

	return View::make('categories/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

/*
	Edit
*/
Route::get('admin/categories/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['category'] = Category::find($id);

	return View::make('categories/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/categories/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('title', 'slug', 'description'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('categories.missing_title'));

	if($errors = $validator->errors()) {
		Input::flash();
		
		Notify::error($errors);

		return Response::redirect('admin/categories/edit/' . $id);
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	Category::update($id, $input);

	Notify::success(__('categories.category_success_updated'));

	return Response::redirect('admin/categories/edit/' . $id);
}));

/*
	Add
*/
Route::get('admin/categories/add', array('before' => 'auth', 'do' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::make('categories/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/categories/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('title', 'slug', 'description'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('categories.missing_title'));

	if($errors = $validator->errors()) {
		Input::flash();
		
		Notify::error($errors);

		return Response::redirect('admin/categories/add');
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	Category::create($input);

	Notify::success(__('categories.category_success_created'));

	return Response::redirect('admin/categories');
}));
