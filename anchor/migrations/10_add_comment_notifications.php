<?php

class Migration_add_comment_notifications extends Migration {

	public function up() {
		if( ! Query::table('meta')->where('key', '=', 'comment_notifications')->count()) {
			Query::table('meta')->insert(array(
				'key' => 'comment_notifications',
				'value' => 0
			));
		}
	}

	public function down() {
		Query::table('meta')->where('key', '=', 'comment_notifications')->delete();
	}

}