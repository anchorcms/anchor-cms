<?php

class Migration_add_keywords extends Migration {

	public function up() {
		$table = Base::table('meta');

		if($this->has_table($table)) {
			if(!Query::table($table)->where('key', '=', 'keywords')->count()) {
				Query::table($table)->insert(array(
					'key' => 'keywords',
					'value' => ''
				));
			}
		}
	}

	public function down() {}

}