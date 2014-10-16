<?php

class Migration_create_user_meta_table extends Migration {

	public function up() {
		$table = Base::table('user_meta');

		if(!$this->has_table($table)) {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`user` int(6) NOT NULL,
				`extend` int(6) NOT NULL,
				`data` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `item` (`user`),
				KEY `extend` (`extend`)
			) ENGINE=InnoDB";

			DB::ask($sql);
		}

		$table2 = Base::table('extend');

		if($this->has_table($table2)) {
			$sql2 = "ALTER TABLE `" . $table2 . "` CHANGE `type` `type` ENUM('post','page','category','user') NOT NULL";
			DB::ask($sql2);
		}
	}

	public function down() {}

}
