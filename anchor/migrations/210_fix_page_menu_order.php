<?php

class Migration_fix_page_menu_order extends Migration {

    public function up() {
        $table = Base::table('pages');

        if($this->has_table_column($table, 'menu_order')) {
            $sql  = 'ALTER TABLE `' . $table . '`;';
            $sql .= 'ALTER COLUMN `menu_order` SET DEFAULT 0';
            DB::ask($sql);
        }
    }

    public function down() {}

}
