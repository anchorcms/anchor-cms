<?php

class Migration_enable_pagetypes extends Migration {

	public function up() {
		$table = Base::table('pagetypes');

		if(!$this->has_table($table)) {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
				`key` varchar(6) NOT NULL,
				`value` varchar(6) NOT NULL
			) ENGINE=InnoDB";

			DB::ask($sql);
			Query::table($table)->insert(array(
				'key' => 'all',
				'value' => 'All Pages'
			));
		}

		$table2 = Base::table('extend');

		if($this->has_table($table2)) {
			$sql2 = "ALTER TABLE `" . $table2 . "` ADD `pagetype` VARCHAR(140) NOT NULL DEFAULT 'all' AFTER `type`";
			DB::ask($sql2);
		}

		$table3 = Base::table('pages');

		if($this->has_table($table3)) {
			$sql2 = "ALTER TABLE `" . $table3 . "` ADD `pagetype` VARCHAR(140) NOT NULL DEFAULT 'all' AFTER `slug`";
			DB::ask($sql2);
		}
	}

	public function down() {}

}
