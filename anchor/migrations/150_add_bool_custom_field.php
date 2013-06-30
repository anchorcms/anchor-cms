<?php

class Migration_add_bool_custom_field extends Migration {
    public function up() {
    	$table = $this->prefix('extend');

        $sql = 'ALTER TABLE `' .$table. '` CHANGE COLUMN `field` `field`
        	enum(\'text\', \'html\', \'image\', \'file\', \'bool\') NOT NULL';
        DB::ask($sql);
    }
    public function down() {}
}
