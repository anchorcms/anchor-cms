<?php

class Migration_alter_comments_status extends Migration {

	public function up() {
		$sql = 'ALTER TABLE `comments` CHANGE `status` `status` enum(\'pending\',\'approved\',\'spam\') NOT NULL AFTER `post`';
		DB::query($sql);
	}

	public function down() {}

}