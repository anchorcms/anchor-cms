<?php

abstract class Migration {
	abstract public function up();
	abstract public function down();

	public function has_table($name) {
		$default = Config::get('database.default');
		$db = Config::get('database.connections.' . $default . '.database');

		$sql = 'SELECT table_name FROM information_schema.tables
			WHERE table_schema = ? AND table_name = ?';
		$result = DB::query($sql, array($db, $name));

		return count($result);
	}

	public function has_table_column($table, $name) {
		$default = Config::get('database.default');
		$db = Config::get('database.connections.' . $default . '.database');

		$sql = 'SELECT table_name FROM information_schema.columns
			WHERE table_schema = ? AND table_name = ? AND column_name = ?';
		$result = DB::query($sql, array($db, $table, $name));

		return count($result);
	}
}