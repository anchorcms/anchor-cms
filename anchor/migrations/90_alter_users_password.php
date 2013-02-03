<?php

class Migration_alter_users_password extends Migration {

	public function up() {
		$sql = 'ALTER TABLE `users` CHANGE `password` `password` text NOT NULL AFTER `username`';
		DB::query($sql);
	}

	public function down() {}

}