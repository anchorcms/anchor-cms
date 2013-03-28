<?php

class Migration_create_page_meta_table extends Migration {

	public function up() {
		$table = Base::table('page_meta');

		if( ! $this->has_table($table)) {
			$sql = "CREATE TABLE IF NOT EXISTS `' . $table . '` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`page` int(6) NOT NULL,
				`extend` int(6) NOT NULL,
				`data` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `page` (`page`),
				KEY `extend` (`extend`)
			) ENGINE=InnoDB";

			DB::ask($sql);
		}
	}

	public function down() {}

}