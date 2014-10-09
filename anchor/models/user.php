<?php

class User extends Base {

	public static $table = 'users';

	public static function search($params = array()) {
		$query = static::where('status', '=', 'active');

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/users'));
	}

}
