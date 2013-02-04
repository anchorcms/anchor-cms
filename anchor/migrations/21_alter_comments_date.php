<?php

class Migration_alter_comments_date extends Migration {

	public function up() {
		$sql = 'ALTER TABLE `comments` CHANGE `date` `date` datetime NOT NULL AFTER `status`';
		DB::query($sql);
	}

	public function down() {}

}