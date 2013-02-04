<?php

abstract class Migration {
	abstract public function up();
	abstract public function down();

	public function has_table($table) {
		$default = Config::get('database.default');
		$db = Config::get('database.connections.' . $default . '.database');

		$sql = 'SHOW TABLES FROM `' . $db . '`';
		$result = DB::query($sql);

		$tables = array();

		foreach($result as $row) {
			$tables[] = $row->{'Tables_in_' . $db};
		}

		return in_array($table, $tables);
	}

	public function has_table_column($table, $column) {
		$sql = 'SHOW COLUMNS FROM `' . $table . '`';
		$result = DB::query($sql);

		$columns = array();

		foreach($result as $row) {
			$columns[] = $row->Field;
		}

		return in_array($column, $columns);
	}
}