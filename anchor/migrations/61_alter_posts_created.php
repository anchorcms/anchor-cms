<?php

class Migration_alter_posts_created extends Migration {

	public function up() {
		if($this->has_table_column('posts', 'created')) {
			$sql = 'ALTER TABLE `posts` CHANGE `created` `created` datetime NOT NULL AFTER `js`';
			DB::query($sql);
		}
	}

	public function down() {}

}