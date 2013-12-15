<?php

class Migration_alter_comments_status extends Migration {

	public function up() {
		$table = Base::table('comments');

		if($this->has_table($table)) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `status` `status` enum(\'pending\',\'approved\',\'spam\') NOT NULL AFTER `post`';
			DB::ask($sql);
		}
	}

	public function down() {}

}