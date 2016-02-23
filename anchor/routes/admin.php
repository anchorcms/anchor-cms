<?php

/**
 * Admin actions
 */
Route::action('auth', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
});

Route::action('guest', function() {
	if(Auth::user()) return Response::redirect('admin/post');
});

Route::action('csrf', function() {
	if(Request::method() == 'POST') {
		if( ! Csrf::check(Input::get('token'))) {
			Notify::error(array('Invalid token'));

			return Response::redirect('admin/login');
		}
	}
});

Route::action('install_exists', function() {
	if(file_exists('install') && !Session::get('messages.error'))
		Notify::error(array('Please remove the install directory before publishing your site'));
});

/**
 * Admin routing
 */
Route::get('admin', function() {
	if(Auth::guest()) return Response::redirect('admin/login');
	return Response::redirect('admin/panel');
});

/*
	Log in
*/
// Why check if we haven't deleted the install directory, BEFORE we've logged in? Isn't that just unlocking the door for the burglars to enter?
//Route::get('admin/login', array('before' => 'install_exists', 'main' => function() {
Route::get('admin/login', array('before' => 'guest', 'main' => function() {
	if(!Auth::guest()) return Response::redirect('admin/posts');
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('users/login', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/login', array('before' => 'csrf', 'main' => function() {
	$attempt = Auth::attempt(Input::get('user'), Input::get('pass'));

	if( ! $attempt) {
		Notify::error(__('users.login_error'));

		return Response::redirect('admin/login');
	}

	// check for updates
	Update::version();

	if(version_compare(Config::get('meta.update_version'), VERSION, '>')) {
		return Response::redirect('admin/upgrade');
	}

	return Response::redirect('admin/panel');
}));

/*
	Log out
*/
Route::get('admin/logout', function() {
	Auth::logout();
	Notify::notice(__('users.logout_notice'));
	return Response::redirect('admin/login');
});

/*
	Amnesia
*/
Route::get('admin/amnesia', array('before' => 'guest', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('users/amnesia', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/amnesia', array('before' => 'csrf', 'main' => function() {
	$email = Input::get('email');

	$validator = new Validator(array('email' => $email));
	$query = User::where('email', '=', $email);

	$validator->add('valid', function($email) use($query) {
		return $query->count();
	});

	$validator->check('email')
		->is_email(__('users.email_missing'))
		->is_valid(__('users.email_not_found'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/amnesia');
	}

	$user = $query->fetch();
	Session::put('user', $user->id);

	$token = noise(8);
	Session::put('token', $token);

	$uri = Uri::full('admin/reset/' . $token);
	$subject = __('users.recovery_subject');
	$msg = __('users.recovery_message', $uri);

	mail($user->email, $subject, $msg);

	Notify::success(__('users.recovery_sent'));

	return Response::redirect('admin/login');
}));

/*
	Reset password
*/
Route::get('admin/reset/(:any)', array('before' => 'guest', 'main' => function($key) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['key'] = ($token = Session::get('token'));

	if($token != $key) {
		Notify::error(__('users.recovery_expired'));

		return Response::redirect('admin/login');
	}

	return View::create('users/reset', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/reset/(:any)', array('before' => 'csrf', 'main' => function($key) {
	$password = Input::get('pass');
	$token = Session::get('token');
	$user = Session::get('user');

	if($token != $key) {
		Notify::error(__('users.recovery_expired'));

		return Response::redirect('admin/login');
	}

	$validator = new Validator(array('password' => $password));

	$validator->check('password')
		->is_max(6, __('users.password_too_short', 6));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/reset/' . $key);
	}

	User::update($user, array('password' => Hash::make($password)));

	Session::erase('user');
	Session::erase('token');

	Notify::success(__('users.password_reset'));

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
	List extend
*/
Route::get('admin/extend', array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::create('extend/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

Route::post('admin/get_fields', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('id', 'pagetype'));

	// get the extended fields
	$vars['fields'] = Extend::fields('page', -1, $input['pagetype']);

	$html = View::create('pages/fields', $vars)->render();
	$token = '<input name="token" type="hidden" value="' . Csrf::token() . '">';

	return Response::json(array(
		'token' => $token,
		'html' => $html
	));
}));

/*
	Upload an image
*/
Route::post('admin/upload', array('before' => 'auth', 'main' => function() {
	$uploader = new Uploader(PATH . 'content', array('png', 'jpg', 'bmp', 'gif', 'pdf'));
	$filepath = $uploader->upload($_FILES['file']);

	$uri = Config::app('url', '/') . 'content/' . basename($filepath);
	$output = array('uri' => $uri);

	return Response::json($output);
}));

/*
	404 error
*/
Route::error('404', function() {
	return Response::error(404);
});
