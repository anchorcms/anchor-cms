<?php

class Migration_rename_config_files extends Migration {

	public function up() {
		if(is_writable($src = APP . 'config/application.php')) {
			rename($src, APP . 'config/app.php');
		}

		if(is_writable($src = APP . 'config/database.php')) {
			rename($src, APP . 'config/db.php');
		}
	}

	public function down() {}

}