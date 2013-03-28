<?php

class Migration_drop_posts_custom_fields extends Migration {

	public function up() {
		$table = Base::table('post_meta');

		if($this->has_table_column($table, 'custom_fields')) {
			$sql = 'ALTER TABLE `' . $table . '` DROP `custom_fields`';
			DB::ask($sql);
		}
	}

	public function down() {}

}