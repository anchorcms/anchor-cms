<?php

class Migration_alter_post_page_content extends Migration {

    public function up() {
        $table = Base::table('pages');
        $table2 = Base::table('posts');

        if($this->has_table_column($table, 'content')) {
            $sql  = 'ALTER TABLE `' . $table . '` ';
            $sql .= 'CHANGE `content` `markdown` TEXT';
            DB::ask($sql);
        }

        if(!$this->has_table_column($table, 'html') && $this->has_table_column($table, 'markdown')) {
            $sql  = 'ALTER TABLE `' . $table . '` ';
            $sql .= 'ADD `html` TEXT NOT NULL AFTER `markdown`';
            DB::ask($sql);

            $pages = Page::sort('menu_order', 'desc')->get();
            foreach ($pages as $page) {
                Page::update($page->id, array(
                    'html' => parse($page->markdown)
                ));
            }
        }

        if(!$this->has_table_column($table2, 'markdown') && $this->has_table_column($table2, 'html')) {
            $sql  = 'ALTER TABLE `' . $table2 . '` ';
            $sql .= 'ADD `markdown` TEXT NOT NULL AFTER `description`';
            DB::ask($sql);

            $migrate_data_sql = 'update `' . $table2 . '` set `markdown` = `html`, `html` = "";';
            DB::ask($migrate_data_sql);

            $posts = Post::sort('created', 'desc')->get();
            foreach ($posts as $post) {
                Post::update($post->id, array(
                    'html' => parse($post->markdown)
                ));
            }
        }
    }

    public function down() {}

}
