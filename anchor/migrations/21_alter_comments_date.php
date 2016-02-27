<?php

class Migration_alter_comments_date extends Migration {

	public function up() {
		$table = Base::table('comments');
		$default = Config::db('default');

		if($this->has_table($table) && strcmp($default, 'mysql') == 0) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `date` `date` datetime NOT NULL AFTER `status`';
			DB::ask($sql);
		}
	}

	public function down() {}

}
