<?php

class Migration_insert_meta_key extends Migration {

    public function up() {
        $table = Base::table('meta');

        if($this->has_table($table)) {
            Query::table($table)->insert(array(
                'key' => 'show_all_posts',
                'value' => '0'
            ));
        }
    }

    public function down() {}

}
