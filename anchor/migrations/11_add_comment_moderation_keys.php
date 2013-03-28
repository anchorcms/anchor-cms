<?php

class Migration_add_comment_moderation_keys extends Migration {

	public function up() {
		$table = Base::table('meta');

		if($this->has_table($table)) {
			if( ! Query::table($table)->where('key', '=', 'comment_moderation_keys')->count()) {
				Query::table($table)->insert(array(
					'key' => 'comment_moderation_keys',
					'value' => ''
				));
			}
		}
	}

	public function down() {}

}