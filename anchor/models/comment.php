<?php

class Comment extends Model {

	public static $table = 'comments';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, url('comments'));
	}

}