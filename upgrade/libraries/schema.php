<?php

class Schema {

	public $connection;

	public function __construct($connection) {
		$this->connection = $connection;
	}

	public function has($table, $column = '', $value = '') {
		if($value) {
			$sql = "select * from `" . $table . "` where `" . $column . "` = '" . $value . "'";
		} elseif($column) {
			$sql = "show columns from `" . $table . "` like '" . $column . "'";
		} else {
			$sql = "show tables like `" . $table . "`";
		}

		$result = $this->connection->query($sql);

		dd($result);

		return $result;
	}

}