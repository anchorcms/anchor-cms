<?php namespace System;

use System\Database\Query;

class Model {

	public static $table;

	protected static $cache = array();

	private $id;

	private $data = array();

	public function __construct($id = null) {
		if( ! is_null($id)) {
			$row = Query::table(static::$table)->where('id', '=', $id)->fetch();
			$this->id = $id;
			$this->populate($row);
		}
	}

	public function __get($key) {
		if(isset($this->data[$key])) {
			return $this->data[$key];
		}
	}

	public function __set($key, $value) {
		$this->data[$key] = $value;
	}

	public static function find($id) {
		$class = get_called_class();
		$item = $id . '-' . $class;

		if(isset(static::$cache[$item])) {
			return static::$cache[$item];
		}

		static::$cache[$item] = new $class($id);

		return static::$cache[$item];
	}

	public function populate($data) {
		if(is_object($data)) {
			$data = get_object_vars($data);
		}

		$this->data = $data;
	}

	public function save() {
		if(is_null($this->id)) {
			$this->id = static::create($this->data);
		}
		else {
			static::update($this->id, $this->data);
		}
	}

	public function delete() {
		$query = Query::table(static::$table)->where('id', '=', $this->id);
		
		$this->id = null;
		$this->data = array();

		return $query->delete();
	}

	public static function where($column, $operator, $value) {
		return Query::table(static::$table)->where($column, $operator, $value);
	}

	public static function create($data) {
		return Query::table(static::$table)->insert_get_id($data);
	}

	public static function update($id, $data) {
		return Query::table(static::$table)->where('id', '=', $id)->update($data);
	}

	public static function all() {
		return Query::table(static::$table)->get();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

		return new Paginator($results, $count, $page, $perpage, Uri::current());
	}

}