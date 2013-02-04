<?php

class Migration_add_posts_category extends Migration {

	public function up() {
		if( ! $this->has_table_column('posts', 'category')) {
			$sql = 'ALTER TABLE `posts` ADD `category` int(6) NOT NULL AFTER `author`';
			DB::query($sql);
		}
	}

	public function down() {
		if($this->has_table_column('posts', 'category')) {
			$sql = 'ALTER TABLE `posts` DROP `category`';
			DB::query($sql);
		}
	}

}