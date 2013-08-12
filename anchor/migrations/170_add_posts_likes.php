<?php

class Migration_add_posts_likes extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('posts');

		if( ! $this->has_table_column($table, 'likes')) {
			$sql = 'ALTER TABLE `' . $table . '` ADD `likes` int(6) NOT NULL';
			DB::ask($sql);
		}
	}

	public function down() {}

}