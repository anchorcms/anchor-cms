<?php

class Migration_create_post_meta_table extends Migration {

	public function up() {
		$table = Base::table('post_meta');

		if( ! $this->has_table($table)) {
			$sql = "CREATE TABLE IF NOT EXISTS `' . $table . '` (
				`id` int(6) NOT NULL AUTO_INCREMENT,
				`post` int(6) NOT NULL,
				`extend` int(6) NOT NULL,
				`data` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `item` (`post`),
				KEY `extend` (`extend`)
			) ENGINE=InnoDB";

			DB::ask($sql);
		}
	}

	public function down() {}

}