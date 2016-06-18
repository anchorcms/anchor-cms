<?php
class Migration_add_updated_fields_to_tables extends Migration
{

    public function up()
    {
        $posts = Base::table('posts');

        if ($this->has_table($posts)) {
            if (!$this->has_table_column($posts, 'updated')) {
                $sql  = 'ALTER TABLE `' . $posts . '` ';
                $sql .= 'ADD COLUMN `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created`';
                DB::ask($sql);
            }
        }

        $pages = Base::table('pages');

        if ($this->has_table($pages)) {
            if (!$this->has_table_column($pages, 'updated')) {
                $sql  = 'ALTER TABLE `' . $pages . '` ';
                $sql .= 'ADD COLUMN `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
                DB::ask($sql);
            }
        }

        $users = Base::table('users');

        if ($this->has_table($users)) {
            if (!$this->has_table_column($users, 'updated')) {
                $sql  = 'ALTER TABLE `' . $users . '` ';
                $sql .= 'ADD COLUMN `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
                DB::ask($sql);
            }
        }
    }

    public function down()
    {
        $posts = Base::table('posts');

        if ($this->has_table($posts)) {
            if ($this->has_table_column($posts, 'updated')) {
                $sql  = 'ALTER TABLE `' . $posts . '` ';
                $sql .= 'DROP COLUMN `updated`';
                DB::ask($sql);
            }
        }

        $pages = Base::table('pages');

        if ($this->has_table($pages)) {
            if ($this->has_table_column($pages, 'updated')) {
                $sql  = 'ALTER TABLE `' . $pages . '` ';
                $sql .= 'DROP COLUMN `updated`';
                DB::ask($sql);
            }
        }

        $users = Base::table('users');

        if ($this->has_table($users)) {
            if ($this->has_table_column($users, 'updated')) {
                $sql  = 'ALTER TABLE `' . $users . '` ';
                $sql .= 'DROP COLUMN `updated`';
                DB::ask($sql);
            }
        }
    }
}