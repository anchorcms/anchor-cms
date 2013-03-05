<?php

class Migration_create_extend_table extends Migration {

	public function up() {
		$table = Base::table('extend');

		if( ! $this->has_table($table)) {
			$sql = "CREATE TABLE IF NOT EXISTS `' . $table . '` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`type` enum('post','page') NOT NULL,
				`field` enum('text','html','image','file') NOT NULL,
				`key` varchar(160) NOT NULL,
				`label` varchar(160) NOT NULL,
				`attributes` text NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB";

			DB::ask($sql);
		}
	}

	public function down() {}

}