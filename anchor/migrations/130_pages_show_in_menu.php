<?php

class Migration_pages_show_in_menu extends Migration {

	public function up() {
		$table = Base::table('pages');

		if( ! $this->has_table_column($table, 'show_in_menu')) {
			$sql = 'ALTER TABLE `' . $table . '` ADD `show_in_menu` tinyint(1) NOT NULL';
			DB::ask($sql);
		}
	}

	public function down() {}

}