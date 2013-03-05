<?php

class Migration_add_comment_notifications extends Migration {

	public function up() {
		$table = Base::table('meta');

		if($this->has_table($table)) {
			if( ! Query::table($table)->where('key', '=', 'comment_notifications')->count()) {
				Query::table($table)->insert(array(
					'key' => 'comment_notifications',
					'value' => 0
				));
			}
		}
	}

	public function down() {}

}