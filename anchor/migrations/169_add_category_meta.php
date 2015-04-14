<?php

class Migration_add_category_meta extends Migration {

    public function up() {
        $table = Base::table('category_meta');

        if(!$this->has_table($table)) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
                `id` int(6) NOT NULL AUTO_INCREMENT,
                `category` int(6) NOT NULL,
                `extend` int(6) NOT NULL,
                `data` text NOT NULL,
                PRIMARY KEY (`id`),
                KEY `item` (`category`),
                KEY `extend` (`extend`)
            ) ENGINE=InnoDB";

            DB::ask($sql);
        }
    }

    public function down() {}

}
