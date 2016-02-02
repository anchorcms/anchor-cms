<?php

abstract class Migration {
	abstract public function up();
	abstract public function down();

	public function has_table($table) {
		$default = Config::db('default');
		$db = Config::db('connections.' . $default . '.database');

		if(strcmp($default, 'mysql') == 0) {
			$sql = 'SHOW TABLES FROM `' . $db . '`';
		} elseif(strcmp($default, 'sqlite') == 0) {
			$sql = 'SELECT name FROM sqlite_master WHERE type=\'table\'';
		}
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
			$default = Config::db('default');
			
			if(strcmp($default, 'mysql') == 0) {
				$sql = 'SHOW COLUMNS FROM `' . $table . '`';
			} elseif(strcmp($default, 'sqlite') == 0) {
				$sql = 'PRAGMA table_info("' . $table . '")';
			}
			
			list($result, $statement) = DB::ask($sql);
			$statement->setFetchMode(PDO::FETCH_OBJ);

			$columns = array();

			if(strcmp($default, 'mysql') == 0) {
				foreach($statement->fetchAll() as $row) {
					$columns[] = $row->Field;
				}
			} elseif(strcmp($default, 'sqlite') == 0) {
				foreach($statement->fetchAll() as $row) {
					$columns[] = $row->name;
				}
			}


			return in_array($column, $columns);
		}
		else return false;
	}
}
