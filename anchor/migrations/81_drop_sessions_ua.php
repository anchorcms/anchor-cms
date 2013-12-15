<?php

class Migration_drop_sessions_ua extends Migration {

	public function up() {
		$table = Base::table('sessions');

		if($this->has_table_column($table, 'ua')) {
			$sql = 'ALTER TABLE `' . $table . '` DROP `ua`';
			DB::ask($sql);
		}
	}

	public function down() {}

}