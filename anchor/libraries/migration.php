<?php

abstract class Migration {
	abstract public function up();
	abstract public function down();

	public function has_table($table) {
		$default = Config::db('default');
		$db = Config::db('connections.' . $default . '.database');

		$sql = 'SHOW TABLES FROM `' . $db . '`';
		list($result, $statement) = DB::ask($sql);
		$statement->setFetchMode(PDO::FETCH_NUM);

		$tables = array();

		foreach($statement->fetchAll() as $row) {
			$tables[] = $row[0];
		}

		return in_array($table, $tables);
	}

	public function has_table_column($table, $column) {
		if($this->has_table($table)) {
			$sql = 'SHOW COLUMNS FROM `' . $table . '`';
			list($result, $statement) = DB::ask($sql);
			$statement->setFetchMode(PDO::FETCH_OBJ);

			$columns = array();

			foreach($statement->fetchAll() as $row) {
				$columns[] = $row->Field;
			}

			return in_array($column, $columns);
		}
		else return false;
	}
}
