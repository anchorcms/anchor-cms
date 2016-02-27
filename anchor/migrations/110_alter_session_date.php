<?php

class Migration_alter_session_date extends Migration {

	public function up() {
		$table = Base::table('sessions');
		$default = Config::db('default');

		if($this->has_table_column($table, 'date') && strcmp($default, 'mysql') == 0) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `date` `expire` int(10) NOT NULL AFTER `id`';
			DB::ask($sql);
		}
	}

	public function down() {}

}
