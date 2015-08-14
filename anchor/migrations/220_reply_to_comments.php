<?php

class Migration_reply_to_comments extends Migration
{
	public function up()
	{
		$table = Base::table('comments');

		DB::ask("ALTER TABLE `{$table}` ADD `reply_to` INT(6) UNSIGNED NOT NULL DEFAULT '0'");
	}

	public function down()
	{
		$table = Base::table('comments');

		DB::ask("ALTER TABLE `{$table}` DROP `reply_to`");
	}
}
