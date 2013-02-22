<?php

class Migration_add_comment_moderation_keys extends Migration {

	public function up() {
		if($this->has_table('meta')) {
			if( ! Query::table('meta')->where('key', '=', 'comment_moderation_keys')->count()) {
				Query::table('meta')->insert(array(
					'key' => 'comment_moderation_keys',
					'value' => ''
				));
			}
		}
	}

	public function down() {}

}