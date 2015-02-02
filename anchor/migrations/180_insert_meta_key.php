<?php

class Migration_insert_meta_key extends Migration {

    public function up() {
        $table = Base::table('meta');

        if($this->has_table($table)) {

            if( ! Query::table($table)->where('key', '=', 'show_all_posts')->count()) {
                 Query::table($table)->insert(array(
                    'key' => 'show_all_posts',
                    'value' => 0
                ));
            } else {
                Query::table($table)->where('key', '=', 'show_all_posts')->update(array('value' => 0));
            }
        }
    }

    public function down() {}

}
