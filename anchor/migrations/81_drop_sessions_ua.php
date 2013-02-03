<?php

class Migration_drop_sessions_ua extends Migration {

	public function up() {
		if($this->has_table_column('sessions', 'ua')) {
			$sql = 'ALTER TABLE `sessions` DROP `ua`';
			DB::query($sql);
		}
	}

	public function down() {}

}