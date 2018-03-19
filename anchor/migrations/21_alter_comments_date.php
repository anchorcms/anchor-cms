<?php

class Migration_alter_comments_date extends Migration
{

    public function up()
    {
        $table = Base::table('comments');

        if ($this->has_table($table)) {
            $sql = 'ALTER TABLE `' . $table . '` CHANGE `date` `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`';
            DB::ask($sql);
        }
    }

    public function down()
    {
    }
}
