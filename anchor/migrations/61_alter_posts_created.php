<?php

class Migration_alter_posts_created extends Migration
{

    public function up()
    {
        $table = Base::table('posts');

        if ($this->has_table_column($table, 'created')) {
            $sql = 'ALTER TABLE `' . $table . '` CHANGE `created` `created` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `js`';
            DB::ask($sql);
        }
    }

    public function down()
    {
    }
}
