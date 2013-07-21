<?php

class Migration_alter_comments_date extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('comments');

		if($this->has_table($table)) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `date` `date` datetime NOT NULL AFTER `status`';
			DB::ask($sql);
		}
	}

	public function down() {}

}