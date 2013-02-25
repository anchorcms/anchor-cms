<?php

class Migration_add_posts_category extends Migration {

	public function up() {
		$table = Base::table('posts');

		if( ! $this->has_table_column($table, 'category')) {
			$sql = 'ALTER TABLE `' . $table . '` ADD `category` int(6) NOT NULL AFTER `author`';
			DB::query($sql);
		}
	}

	public function down() {}

}