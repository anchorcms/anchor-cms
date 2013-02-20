<?php

class Migration_alter_session_date extends Migration {

	public function up() {
		if($this->has_table_column('sessions', 'date')) {
			$sql = 'ALTER TABLE `sessions` CHANGE `date` `expire` int(10) NOT NULL AFTER `id`';
			DB::query($sql);
		}
	}

	public function down() {
		if($this->has_table_column('sessions', 'expire')) {
			$sql = 'ALTER TABLE `sessions` CHANGE `expire` `date` datetime NOT NULL AFTER `id`';
			DB::query($sql);
		}
	}

}