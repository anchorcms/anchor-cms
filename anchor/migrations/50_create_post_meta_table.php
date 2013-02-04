<?php

class Migration_create_post_meta_table extends Migration {

	public function up() {
		if( ! $this->has_table('post_meta')) {
			$sql = "CREATE TABLE IF NOT EXISTS `post_meta` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`post` int(6) NOT NULL,
				`extend` int(6) NOT NULL,
				`data` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `item` (`post`),
				KEY `extend` (`extend`)
			) ENGINE=InnoDB";

			DB::query($sql);
		}
	}

	public function down() {
		$sql = 'DROP TABLE `post_meta`';
		DB::query($sql);
	}

}