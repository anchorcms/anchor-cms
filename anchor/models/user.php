<?php

class User extends Base {

	public static $table = 'users';

	public static function input() {
		return filter_var_array(Input::get(), array(
			'username' => FILTER_SANITIZE_STRING,
			'email' => FILTER_SANITIZE_EMAIL,
			'real_name' => FILTER_SANITIZE_STRING,
			'bio' => FILTER_SANITIZE_STRING,
			'status' => FILTER_SANITIZE_STRING,
			'role' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW
		));
	}

	public static function validate($input) {
		$validator = new Validator($input);

		$validator->check('username')
			->is_max(2, __('users.username_missing', 2));

		$validator->check('email')
			->is_email(__('users.email_missing'));

		if($input['password']) {
			$validator->check('password')
				->is_max(6, __('users.password_too_short', 6));
		}

		return $validator->errors();
	}

	public static function update($id, $input) {
		if($input['password']) {
			$input['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
		}

		parent::update($id, $input);
	}

	public static function search($params = array()) {
		$query = static::where('status', '=', 'active');

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('users'));
	}

}