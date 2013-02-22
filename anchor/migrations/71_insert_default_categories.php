<?php

class Migration_insert_default_categories extends Migration {

	public function up() {
		if($this->has_table('categories')) {
			if( ! Query::table('categories')->count()) {
				Query::table('categories')->insert(array(
					'title' => 'Uncategorised',
					'slug' => 'uncategorised',
					'description' => 'Ain\'t no category here.'
				));
			}
		}
	}

	public function down() {}

}