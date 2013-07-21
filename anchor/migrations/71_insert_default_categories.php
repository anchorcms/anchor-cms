<?php

class Migration_insert_default_categories extends Migrations\Migration {

	public function up() {
		$table = $this->prefix('categories');

		if($this->has_table($table)) {
			if( ! Query::table($table)->count()) {
				Query::table($table)->insert(array(
					'title' => 'Uncategorised',
					'slug' => 'uncategorised',
					'description' => 'Ain\'t no category here.'
				));
			}
		}
	}

	public function down() {}

}