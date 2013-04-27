<?php

class Migration_create_categories_table extends Migration {

	public function up() {
		$table = Base::table('categories');

		if( ! $this->has_table($table)) {
			$sql = 'CREATE TABLE IF NOT EXISTS `' . $table . '` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`title` varchar(150) NOT NULL,
				`slug` varchar(40) NOT NULL,
				`description` text NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB';

			DB::query($sql);
		}
	}

	public function down() {}

}