<?php

class Migration_alter_meta_field extends Migration
{
    public function up()
    {
        $table = Base::table('extend');
        if ($this->has_table($table)) {
            $sql = "ALTER TABLE `" . $table . "` CHANGE `field` `field` enum('text','html','image','file','toggle') NOT NULL AFTER `type`";
            DB::ask($sql);
        }
    }
    public function down()
    {
        $table = Base::table('extend');
        if ($this->has_table($table)) {
            $sql = "ALTER TABLE `" . $table . "` CHANGE `field` `field` enum('text','html','image','file') NOT NULL AFTER `type`";
            DB::ask($sql);
        }
    }
}
