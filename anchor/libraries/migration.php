<?php

abstract class Migration {
	abstract public function up();
	abstract public function down();

	public function has_table($table) {
		$default = Config::db('default');
		$db = Config::db('connections.' . $default . '.database');

		$sql = 'SHOW TABLES FROM `' . $db . '`';
		$statement = DB::query($sql);
		$statement->setFetchMode(PDO::FETCH_OBJ);

		$tables = array();

		foreach($statement->fetchAll() as $row) {
			$tables[] = $row->{'Tables_in_' . $db};
		}

		return in_array($table, $tables);
	}

	public function has_table_column($table, $column) {
		$sql = 'SHOW COLUMNS FROM `' . $table . '`';
		$statement = DB::query($sql);
		$statement->setFetchMode(PDO::FETCH_OBJ);

		$columns = array();

		foreach($statement->fetchAll() as $row) {
			$columns[] = $row->Field;
		}

		return in_array($column, $columns);
	}
}