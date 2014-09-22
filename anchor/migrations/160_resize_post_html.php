<?php

class Migration_resize_post_html extends Migration {

	public function up() {
		$table = Base::table('posts');

		if($this->has_table_column($table, 'html')) {
			$sql = 'ALTER TABLE `' . $table . '` MODIFY COLUMN `html` MEDIUMTEXT NOT NULL';
			DB::ask($sql);
		}
	}

	public function down() {
		$table = Base::table('posts');

		if($this->has_table_column($table, 'html')) {
			$sql = 'ALTER TABLE `' . $table . '` MODIFY COLUMN `html` TEXT NOT NULL';
			DB::ask($sql);
		}
	}

}
