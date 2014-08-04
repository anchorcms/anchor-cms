<?php

class Migration_add_featured_posts extends Migration {

	public function up() {
		$table = Base::table('posts');

		if(!$this->has_table_column($table, 'featured')) {
			$sql = 'ALTER TABLE '. $table .' ADD `featured` TINYINT(1) NOT NULL DEFAULT \'0\' AFTER `comments`';
			DB::ask($sql);
		}
        
	}

	public function down() {}

}