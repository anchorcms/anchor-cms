<?php

class Migration_add_page_parent extends Migration {

	public function up() {
		$table = Base::table('pages');

		if( ! $this->has_table_column($table, 'parent')) {
			$sql = 'ALTER TABLE `' . $table . '` ADD `parent` int(6) NOT NULL AFTER `id`';
			DB::ask($sql);
		}
	}

	public function down() {}

}