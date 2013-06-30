<?php

class Migration_add_posts_per_page_in_admin_area extends Migration {

	public function up() {
		$table = $this->prefix('meta');

		if($this->has_table($table)) {
			$key = 'admin_posts_per_page';
			$query = Query::table($table)->where('key', '=', $key);

			if($query->count() == 0) {
				Query::table($table)->insert(array('key' => $key, 'value' => 10));
			}
		}
	}

	public function down() {}

}
