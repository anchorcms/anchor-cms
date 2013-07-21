<?php

class Migration_alter_extend_table extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('extend');

		if($this->has_table_column($table, 'type') and ! $this->has_table_column($table, 'data_type')) {
			$sql = 'ALTER TABLE `' . $table . '`
				CHANGE `type` `data_type` varchar(20) NOT NULL AFTER `id`';

			DB::ask($sql);
		}

		if($this->has_table_column($table, 'field') and ! $this->has_table_column($table, 'field_type')) {
			$sql = 'ALTER TABLE `' . $table . '`
				CHANGE `field` `field_type` varchar(20) NOT NULL AFTER `data_type`';

			DB::ask($sql);
		}
	}

	public function down() {}

}
