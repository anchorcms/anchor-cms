<?php

class Migration_create_page_meta_table extends Migration {

	public function up() {
		if( ! $this->has_table('page_meta')) {
			$sql = "CREATE TABLE IF NOT EXISTS `page_meta` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`page` int(6) NOT NULL,
				`extend` int(6) NOT NULL,
				`data` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `page` (`page`),
				KEY `extend` (`extend`)
			) ENGINE=InnoDB";

			DB::query($sql);
		}
	}

	public function down() {
		$sql = 'DROP TABLE `page_meta`';
		DB::query($sql);
	}

}