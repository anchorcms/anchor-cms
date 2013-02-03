<?php

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

	return View::make('users/login', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('admin/login', array('before' => 'csrf', 'do' => function() {
	if( ! Auth::attempt(Input::get('user'), Input::get('pass'))) {
		Notify::error(array('Username or password is wrong.'));

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
	return Response::redirect('admin/login');
});

/*
	Amnesia
*/
Route::get('admin/amnesia', function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	return View::make('users/amnesia', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('admin/amnesia', function() {
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

	$token = Str::random(8);
	Session::put('token', $token);

	$uri = Uri::build(array('path' => Uri::make('admin/reset/' . $token)));

	mail($user->email,
		__('users.user_subject_recover', 'Password Reset'),
		__('users.user_email_recover',
			'You have requested to reset your password. To continue follow the link below.' . PHP_EOL . '%s', $uri));

	Notify::success(__('users.user_notice_recover',
		'We have sent you an email to confirm your password change.'));

	return Response::redirect('admin/login');
});

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

	return View::make('users/reset', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});

Route::post('admin/reset/(:any)', function($key) {
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

	Session::forget('user');
	Session::forget('token');

	Notify::success(__('users.user_success_password', 'Your new password has been set. Go and login now!'));

	return Response::redirect('admin/login');
});

/*
	update
*/
Route::get('admin/upgrade', function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	$version = Config::get('meta.update_version');
	$url = 'https://github.com/anchorcms/anchor-cms/archive/%s.zip';

	$vars['version'] = $version;
	$vars['url'] = sprintf($url, $version);

	return View::make('upgrade', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
});