<?php

class Migration_add_comment_moderation_keys extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('meta');

		if($this->has_table($table)) {
			$key = 'comment_moderation_keys';
			$query = Query::table($table)->where('key', '=', $key);

			if($query->count() == 0) {
				Query::table($table)->insert(array('key' => $key, 'value' => ''));
			}
		}
	}

	public function down() {}

}