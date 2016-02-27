<?php

class Migration_alter_users_password extends Migration {

	public function up() {
		$table = Base::table('users');
		$default = Config::db('default');

		if($this->has_table_column($table, 'password') && strcmp($default, 'mysql') == 0) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `password` `password` text NOT NULL AFTER `username`';
			DB::ask($sql);
		}
	}

	public function down() {}

}
