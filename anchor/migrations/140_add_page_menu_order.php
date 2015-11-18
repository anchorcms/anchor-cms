<?php

class Migration_add_page_menu_order extends Migration {

	public function up() {
		$table = Base::table('pages');

		if( ! $this->has_table_column($table, 'menu_order')) {
			$sql = 'ALTER TABLE `' . $table . '` ADD `menu_order` int(4) NOT NULL DEFAULT 0';
			DB::ask($sql);
		}
	}

	public function down() {}

}