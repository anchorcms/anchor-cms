<?php
class Migration_insert_default_dashboard_meta_key extends Migration
{

    public function up()
    {
        $table = Base::table('meta');

        if ($this->has_table($table)) {
            if (! Query::table($table)->where('key', '=', 'dashboard_page')->count()) {
                Query::table($table)->insert(array(
                    'key' => 'dashboard_page',
                    'value' => 'panel'
                ));
            } else {
                Query::table($table)->where('key', '=', 'dashboard_page')->update(array('value' => 'panel'));
            }
        }
    }

    public function down()
    {
    }
}