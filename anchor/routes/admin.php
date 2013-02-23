<?php

/**
 * Admin actions
 */
Route::action('auth', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
});

Route::action('csrf', function() {
	if( ! Csrf::check(Input::get('token'))) {
		Notify::error(array('Invalid token'));

		return Response::redirect('admin/login');
	}
});

/**
 * Admin routing
 */
Route::get('admin', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
	return Response::redirect('admin/posts');
});

/*
	Log in
*/
Route::get('admin/login', function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('users/login', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
});

Route::post('admin/login', array('before' => 'csrf', 'main' => function() {
	$attempt = Auth::attempt(Input::get('user'), Input::get('pass'));

	if( ! $attempt) {
		Notify::error('Username or password is wrong.');

		return Response::redirect('admin/login');
	}

	// check for updates
	Update::version();

	if(version_compare(Config::get('meta.update_version'), VERSION, '>')) {
		return Response::redirect('admin/upgrade');
	}

	return Response::redirect('admin/posts');
}));

/*
	Log out
*/
Route::get('admin/logout', function() {
	Auth::logout();
	Notify::notice('You are now logged out.');
	return Response::redirect('admin/login');
});

/*
	Amnesia
*/
Route::get('admin/amnesia', function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('users/amnesia', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
});

Route::post('admin/amnesia', array('before' => 'csrf', 'main' => function() {
	$email = Input::get('email');

	$validator = new Validator(array('email' => $email));
	$query = User::where('email', '=', $email);

	$validator->add('valid', function($email) use($query) {
		return $query->count();
	});

	$validator->check('email')
		->is_email(__('users.invalid_email', 'Please enter a valid email address.'))
		->is_valid(__('users.invalid_account', 'Account not found.'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/amnesia');
	}

	$user = $query->fetch();
	Session::put('user', $user->id);

	$token = noise(8);
	Session::put('token', $token);

	$uri = 'http://' . $_SERVER['HTTP_HOST'] . Uri::to('admin/reset/' . $token);

	mail($user->email,
		__('users.user_subject_recover', 'Password Reset'),
		__('users.user_email_recover',
			'You have requested to reset your password. To continue follow the link below.' . PHP_EOL . '%s', $uri));

	Notify::success(__('users.user_notice_recover',
		'We have sent you an email to confirm your password change.'));

	return Response::redirect('admin/login');
}));

/*
	Reset password
*/
Route::get('admin/reset/(:any)', function($key) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['key'] = ($token = Session::get('token'));

	if($token != $key) {
		Notify::error(__('users.invalid_account', 'Account not found'));

		return Response::redirect('admin/login');
	}

	return View::create('users/reset', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
});

Route::post('admin/reset/(:any)', array('before' => 'csrf', 'main' => function($key) {
	$password = Input::get('pass');
	$token = Session::get('token');
	$user = Session::get('user');

	if($token != $key) {
		Notify::error(__('users.invalid_account', 'Account not found'));

		return Response::redirect('admin/login');
	}

	$validator = new Validator(array('password' => $password));

	$validator->check('password')
		->is_max(6, __('users.password_too_short', 'Your password must be at least %s characters long', 6));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/reset/' . $key);
	}

	User::update($user, array('password' => Hash::make($password)));

	Session::erase('user');
	Session::erase('token');

	Notify::success(__('users.user_success_password', 'Your new password has been set. Go and login now!'));

	return Response::redirect('admin/login');
}));

/*
	Upgrade
*/
Route::get('admin/upgrade', function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	$version = Config::meta('update_version');
	$url = 'https://github.com/anchorcms/anchor-cms/archive/%s.zip';

	$vars['version'] = $version;
	$vars['url'] = sprintf($url, $version);

	return View::create('upgrade', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
});

/*
	List Categories
*/
Route::get(array('admin/categories', 'admin/categories/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['categories'] = Category::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('categories/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit Category
*/
Route::get('admin/categories/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['category'] = Category::find($id);

	return View::create('categories/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/categories/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('title', 'slug', 'description'));

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

	$input['slug'] = slug($input['slug']);

	Category::update($id, $input);

	Notify::success(__('categories.category_success_updated'));

	return Response::redirect('admin/categories/edit/' . $id);
}));

/*
	Add Category
*/
Route::get('admin/categories/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('categories/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/categories/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('title', 'slug', 'description'));

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

	$input['slug'] = slug($input['slug']);

	Category::create($input);

	Notify::success(__('categories.category_success_created'));

	return Response::redirect('admin/categories');
}));

/*
	Delete Category
*/
Route::get('admin/categories/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	$total = Query::table(Category::$table)->count();

	if($total == 1) {
		Notify::error(__('categories.category_failed_delete'));

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

	Notify::success(__('categories.category_success_delete', 'Category deleted'));

	return Response::redirect('admin/categories');
}));

/*
	List Comments
*/
Route::get(array(
	'admin/comments',
	'admin/comments/(pending|approved|spam)',
	'admin/comments/(pending|approved|spam)/(:num)',
	'admin/comments/(pending|approved|spam)/(:num)/(:num)'
), array('before' => 'auth', 'main' => function($status = 'all', $page = 1, $perpage = 10) {
	$vars['messages'] = Notify::read();

	$query = Query::table(Base::table(Comment::$table));

	if(in_array($status, array('pending', 'approved', 'spam'))) {
		$query->where('status', '=', $status);
	}

	$count = $query->count();
	$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

	$vars['comments'] = new Paginator($results, $count, $page, $perpage, Uri::to('comments/' . $status));

	return View::create('comments/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit Comment
*/
Route::get('admin/comments/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['comment'] = Comment::find($id);

	$vars['statuses'] = array(
		'approved' => __('comments.approved', 'Approved'),
		'pending' => __('comments.pending', 'Pending'),
		'spam' => __('comments.spam', 'Spam')
	);

	return View::create('comments/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/comments/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('name', 'email', 'text', 'status'));

	$validator = new Validator($input);

	$validator->check('name')
		->is_max(3, __('comments.missing_name', 'Please enter a name'));

	$validator->check('text')
		->is_max(3, __('comments.missing_text', 'Please enter comment text'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/comments/edit/' . $id);
	}

	Comment::update($id, $input);

	Notify::success(__('comments.updated', 'Your comment has been updated.'));

	return Response::redirect('admin/comments/' . $input['status']);
}));

/*
	Delete Comment
*/
Route::get('admin/comments/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->delete();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Approve Comment
*/
Route::get('admin/comments/status/approve/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'approved';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Unapprove Comment
*/
Route::get('admin/comments/status/unapprove/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Spam Comment
*/
Route::get('admin/comments/status/spam/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'spam';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	Not Spam Comment
*/
Route::get('admin/comments/status/notspam/(:num)', array('before' => 'auth', 'main' => function($id) {
	$comment = Comment::find($id);
	$status = $comment->status;

	$comment->status = 'pending';
	$comment->save();

	return Response::redirect('admin/comments/' . $status);
}));

/*
	List extend
*/
Route::get('admin/extend', array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	List Fields
*/
Route::get(array('admin/extend/fields', 'admin/extend/fields/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['extend'] = Extend::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('extend/fields/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Add Field
*/
Route::get('admin/extend/fields/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/fields/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/extend/fields/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('type', 'field', 'key', 'label', 'attributes'));

	if(empty($input['key'])) {
		$input['key'] = $input['label'];
	}

	$input['key'] = slug($input['key'], '_');

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
	Edit Field
*/
Route::get('admin/extend/fields/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
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
}));

Route::post('admin/extend/fields/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('type', 'field', 'key', 'label', 'attributes'));

	if(empty($input['key'])) {
		$input['key'] = $input['label'];
	}

	$input['key'] = slug($input['key'], '_');

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
	Delete Field
*/
Route::get('admin/extend/fields/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	$field = Extend::find($id);

	Query::table($field->type . '_meta')->where('extend', '=', $field->id)->delete();

	$field->delete();

	return Response::redirect('admin/extend/fields');
}));


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

/*
	Add Metadata
*/
Route::get('admin/extend/metadata/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/metadata/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/extend/metadata/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('name', 'value'));

	$validator = new Validator($input);

	$validator->check('name')
		->is_max(3, __('metadata.missing_custom_name', 'Please enter a unique key name'));

	$validator->check('value')
		->is_max(3, __('metadata.missing_custom_value', 'Please enter a value'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/extend/metadata/add');
	}

	Query::table(Base::table('meta'))->insert(array(
		'key' => 'custom_' . slug($input['name'], '_'),
		'value' => $input['value']
	));

	Notify::success(__('metadata.custom_meta_success_created', 'Custom metadata created'));

	return Response::redirect('admin/extend/metadata');
}));


/*
	List Pages
*/
Route::get(array('admin/pages', 'admin/pages/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['pages'] = Page::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('pages/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit Page
*/
Route::get('admin/pages/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['page'] = Page::find($id);
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page', $id);

	return View::create('pages/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/pages/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('name', 'title', 'slug', 'content', 'status', 'redirect'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('pages.missing_title'));

	if($input['redirect']) {
		$validator->check('redirect')
			->is_url( __('pages.missing_redirect', 'Please enter a valid url'));
	}

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/pages/edit/' . $id);
	}

	if(empty($input['name'])) {
		$input['name'] = $input['title'];
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	// convert html to entities
	//$input['content'] = e($input['content']);

	Page::update($id, $input);

	Extend::process('page', $id);

	Notify::success(__('pages.page_success_updated'));

	return Response::redirect('admin/pages/edit/' . $id);
}));

/*
	Add Page
*/
Route::get('admin/pages/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page');

	return View::create('pages/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/pages/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('name', 'title', 'slug', 'content', 'status', 'redirect'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('pages.missing_title', ''));

	if($input['redirect']) {
		$validator->check('redirect')
			->is_url( __('pages.missing_redirect', 'Please enter a valid url'));
	}

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/pages/add');
	}

	if(empty($input['name'])) {
		$input['name'] = $input['title'];
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	// convert html to entities
	//$input['content'] = e($input['content']);

	$id = Page::create($input);

	Extend::process('page', $id);

	Notify::success(__('pages.page_success_created'));

	return Response::redirect('admin/pages');
}));

/*
	Delete Page
*/
Route::get('admin/pages/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	Page::find($id)->delete();

	Notify::success(__('pages.page_success_delete'));

	return Response::redirect('admin/pages');
}));

/*
	List all plugins
*/
Route::get('admin/extend/plugins', array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/plugins/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));


/*
	List all posts and paginate through them
*/
Route::get(array('admin/posts', 'admin/posts/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['posts'] = Post::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('posts/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit post
*/
Route::get('admin/posts/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['article'] = Post::find($id);
	$vars['page'] = Registry::get('posts_page');

	// extended fields
	$vars['fields'] = Extend::fields('post', $id);

	$vars['statuses'] = array(
		'published' => __('posts.published', 'Published'),
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived')
	);

	$vars['categories'] = Category::dropdown();

	return View::create('posts/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/posts/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('html')
		->is_max(3, __('posts.missing_html', 'Please enter your html'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/edit/' . $id);
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	if($input['created']) {
		$input['created'] = Date::format($input['created'], 'Y-m-d H:i:s');
	}
	else {
		unset($input['created']);
	}

	if(is_null($input['comments'])) {
		$input['comments'] = 0;
	}

	// convert html to entities
	//$input['html'] = e($input['html']);

	Post::update($id, $input);

	Extend::process('post', $id);

	Notify::success(__('posts.updated', 'Your article has been updated.'));

	return Response::redirect('admin/posts/edit/' . $id);
}));

/*
	Add new post
*/
Route::get('admin/posts/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['page'] = Registry::get('posts_page');

	// extended fields
	$vars['fields'] = Extend::fields('post');

	$vars['statuses'] = array(
		'published' => __('posts.published', 'Published'),
		'draft' => __('posts.draft', 'Draft'),
		'archived' => __('posts.archived', 'Archived')
	);

	$vars['categories'] = Category::dropdown();

	return View::create('posts/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/posts/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('title', 'slug', 'description', 'created',
		'html', 'css', 'js', 'category', 'status', 'comments'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.missing_title', 'Please enter a title'));

	$validator->check('html')
		->is_max(3, __('posts.missing_html', 'Please enter your html'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/posts/add');
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	if(empty($input['created'])) {
		$input['created'] = date('Y-m-d H:i:s');
	}

	$user = Auth::user();

	$input['author'] = $user->id;

	if(is_null($input['comments'])) {
		$input['comments'] = 0;
	}

	// convert html to entities
	//$input['html'] = e($input['html']);

	$post = Post::create($input);

	Extend::process('post', $post->id);

	$message = __('posts.created', 'Your new article was created, <a href="%s">continue editing</a>.');

	Notify::success(sprintf($message, Uri::to('posts/edit/' . $post->id)));

	return Response::redirect('admin/posts');
}));

/*
	Preview post
*/
Route::post('admin/posts/preview', array('before' => 'auth', 'main' => function() {
	$html = Input::get('html');

	// apply markdown processing
	$md = new Markdown;
	$output = Json::encode(array('html' => $md->transform($html)));

	return Response::create($output, 200, array('Content-Type' => 'application/json'));
}));

/*
	Delete post
*/
Route::get('admin/posts/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	Post::find($id)->delete();

	Comment::where('post', '=', $id)->delete();

	Query::table('post_meta')->where('post', '=', $id)->delete();

	Notify::success(__('posts.post_success_deleted'));

	return Response::redirect('admin/posts');
}));

/*
	List users
*/
Route::get(array('admin/users', 'admin/users/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['users'] = User::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('users/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit user
*/
Route::get('admin/users/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['user'] = User::find($id);

	$vars['statuses'] = array(
		'inactive' => __('users.inactive'),
		'active' => __('users.active')
	);

	$vars['roles'] = array(
		'administrator' => __('users.administrator'),
		'editor' => __('users.editor'),
		'user' => __('users.user')
	);

	return View::create('users/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/users/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('username', 'email', 'real_name', 'bio', 'status', 'role'));
	$password_reset = false;

	if($password = Input::get('password')) {
		$input['password'] = $password;
		$password_reset = true;
	}

	$validator = new Validator($input);

	$validator->add('safe', function($str) use($id) {
		return ($str != 'inactive' and Auth::user()->id == $id);
	});

	$validator->check('status')
		->is_safe(__('users.invalid_status'));

	$validator->check('username')
		->is_max(3, __('users.missing_username'));

	$validator->check('email')
		->is_email(__('users.missing_email'));

	if($password_reset) {
		$validator->check('password')
			->is_max(6, sprintf(__('users.password_too_short'), 6));
	}

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/users/edit/' . $id);
	}

	if($password_reset) {
		$input['password'] = Hash::make($input['password']);
	}

	User::update($id, $input);

	Notify::success(__('users.user_success_updated'));

	return Response::redirect('admin/users/edit/' . $id);
}));

/*
	Add user
*/
Route::get('admin/users/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	$vars['statuses'] = array(
		'inactive' => __('users.inactive'),
		'active' => __('users.active')
	);

	$vars['roles'] = array(
		'administrator' => __('users.administrator'),
		'editor' => __('users.editor'),
		'user' => __('users.user')
	);

	return View::create('users/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/users/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('username', 'email', 'real_name', 'password', 'bio', 'status', 'role'));

	$validator = new Validator($input);

	$validator->check('username')
		->is_max(3, __('users.missing_username'));

	$validator->check('email')
		->is_email(__('users.missing_email'));

	$validator->check('password')
		->is_max(6, sprintf(__('users.password_too_short'), 6));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/users/add');
	}

	$input['password'] = Hash::make($input['password']);

	User::create($input);

	Notify::success(__('users.user_success_created'));

	return Response::redirect('admin/users');
}));

/*
	Delete user
*/
Route::get('admin/users/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	$self = Auth::user();

	if($self->id == $id) {
		Notify::error('You cannot commit suicide');

		return Response::redirect('admin/users/edit/' . $id);
	}

	User::where('id', '=', $id)->delete();

	Notify::success(__('users.user_success_deleted'));

	return Response::redirect('admin/users');
}));