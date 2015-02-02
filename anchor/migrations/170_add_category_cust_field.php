<?php

class Migration_add_category_cust_field extends Migration {

    public function up() {
        $table = Base::table('extend');

        if($this->has_table_column($table, 'type')) {
            $sql = 'ALTER TABLE `' . $table . '` MODIFY COLUMN `type` enum("post", "page", "category") NOT NULL';
            DB::ask($sql);
        }
    }

    public function down() {
        $table = Base::table('extend');

        if($this->has_table_column($table, 'type')) {
            $sql = 'ALTER TABLE `' . $table . '` MODIFY COLUMN `type` enum("post", "page") NOT NULL';
            DB::ask($sql);
        }
    }

}
