<?php

class Migration_add_posts_per_page_in_admin_area extends Migration {

	public function up() {
		$table = Base::table('meta');

		if($this->has_table($table)) {
			if( ! Query::table($table)->where('key', '=', 'admin_posts_per_page')->count()) {
				Query::table($table)->insert(array(
					'key' => 'admin_posts_per_page',
					'value' => 10
				));
			}
		}
	}

	public function down() {}

}
