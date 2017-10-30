<?php

class Migration_alter_posts_created extends Migration
{

    public function up()
    {
        $table = Base::table('posts');

        if ($this->has_table_column($table, 'created')) {
            $sql = 'ALTER TABLE `' . $table . '` CHANGE `created` `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `js`';
            DB::ask($sql);
        }
    }

    public function down()
    {
    }
}
