<?php

return array(

	'users' => 'Users',

	'create_user' => 'Create a new user',
	'add_user' => 'Add a new user',
	'editing_user' => 'Editing %s&rsquo;s Profile',
	'remembered' => 'I know my password',
	'forgotten_password' => 'Forgotten your password?',

	// roles
	'administrator' => 'Admin',
	'administrator_explain' => '',

	'editor' => 'Editor',
	'editor_explain' => '',

	'user' => 'User',
	'user_explain' => '',

	// form fields
	'real_name' => 'Real Name',
	'real_name_explain' => '',

	'bio' => 'Biography',
	'bio_explain' => '',

	'status' => 'Status',
	'status_explain' => '',

	'role' => 'Role',
	'role_explain' => '',

	'username' => 'Username',
	'username_explain' => '',
	'username_missing' => 'Please enter a username, must be at least %s characters',

	'password' => 'Password',
	'password_explain' => '',
	'password_too_short' => 'Password must be at least %s characters',

	'new_password' => 'New Password',

	'email' => 'Email',
	'email_explain' => '',
	'email_missing' => 'Please enter a valid email address',
	'email_not_found' => 'Profile not found.',

	// messages
	'updated' => 'User profile updated.',
	'created' => 'User profile created.',
	'deleted' => 'User profile deleted.',
	'delete_error' => 'You cannot delete your own profile',
	'login_error' => 'Username or password is wrong.',
	'logout_notice' => 'You are now logged out.',
	'recovery_sent' => 'We have sent you an email to confirm your password change.',
	'recovery_expired' => 'Password recovery token has expired, please try again.',
	'password_reset' => 'Your new password has been set. Go and login now!',

	// password recovery email
	'recovery_subject' => 'Password Reset',
	'recovery_message' => 'You have requested to reset your password.' .
		'To continue follow the link below.' . PHP_EOL . '%s',

);