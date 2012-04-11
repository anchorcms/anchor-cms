<?php

class Migrations {

	private static $queries = array();
	
	/*
		Database changes
	*/
	public static function query($sql) {
		static::$queries[] = $sql;
	}

	/*
		Execute
	*/
	public static function apply() {
		foreach(static::$queries as $sql) {
			Db::query($sql);
		}
	}

}