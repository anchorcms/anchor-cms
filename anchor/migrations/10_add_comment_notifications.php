<?php

class Migration_add_comment_notifications extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('meta');

		if($this->has_table($table)) {
			$key = 'comment_notifications';
			$query = Query::table($table)->where('key', '=', $key);

			if($query->count() == 0) {
				Query::table($table)->insert(array('key' => $key, 'value' => 0));
			}
		}
	}

	public function down() {}

}