<?php

class Migration_drop_sessions_ip extends Migration {

	public function up() {
		if($this->has_table_column('sessions', 'ip')) {
			$sql = 'ALTER TABLE `sessions` DROP `ip`';
			DB::query($sql);
		}
	}

	public function down() {}

}