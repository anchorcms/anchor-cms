<?php

class Migration_alter_posts_created extends Migration {

	public function up() {
		$table = Base::table('posts');
		$default = Config::db('default');

		if($this->has_table_column($table, 'created') && strcmp($default, 'mysql') == 0) {
			$sql = 'ALTER TABLE `' . $table . '` CHANGE `created` `created` datetime NOT NULL AFTER `js`';
			DB::ask($sql);
		}
	}

	public function down() {}

}
