<?php

class Schema {
	
	public static function has($table, $column = '', $value = '') {
		if($value) {
			$sql = "select * from `" . $table . "` where `" . $column . "` = '" . $value . "'";
		} elseif($column) {
			$sql = "show columns from `" . $table . "` like '" . $column . "'";
		} else {
			$sql = "show tables like `" . $table . "`";
		}
		return Db::query($sql)->rowCount() > 0;
	}

}