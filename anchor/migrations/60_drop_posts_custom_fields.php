<?php

class Migration_drop_posts_custom_fields extends Migration {

	public function up() {
		if($this->has_table_column('posts', 'custom_fields')) {
			$sql = 'ALTER TABLE `posts` DROP `custom_fields`';
			DB::query($sql);
		}
	}

	public function down() {}

}