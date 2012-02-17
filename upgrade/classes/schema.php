<?php

class Schema {
	
	public static function has($table, $column = '') {
		if($column) {
			$sql = "show coumns from `" . $table . "` like '" . $column . "'";
		} else {
			$sql = "show tables like `" . $table . "`";
		}
		return Db::query($sql)->rowCount() > 0;
	}

}