<?php

use System\config;
use System\database\query;
use System\view;

/**
 * anchor class
 * Checks the installation status and loads all functions and configuration
 */
class anchor
{
    /**
     * Sets up Anchor
     *
     * @return void
     * @throws \ErrorException
     * @throws \OverflowException
     * @throws \Exception
     */
    public static function setup()
    {
        // check installation and show intro
        static::installation();

        // rename config files
        if (is_writable($src = APP . 'config/application.php')) {
            @rename($src, APP . 'config/app.php');
        }

        if (is_writable($src = APP . 'config/database.php')) {
            @rename($src, APP . 'config/db.php');
        }

        // load meta data from the db to the config
        static::meta();

        // import theming functions
        static::functions();

        // check migrations are up to date
        static::migrations();

        // populate registry with globals
        static::register();
    }

    /**
     * Checks whether Anchor is installed
     *
     * @return void
     */
    public static function installation()
    {
        if ( ! is_installed()) {
            echo View::create('intro')->render();

            exit(0);
        }
    }

    /**
     * Loads all meta config settings
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function meta()
    {
        $table = Base::table('meta');
        $meta  = [];

        // load database metadata
        foreach (Query::table($table)->get() as $item) {
            $meta[$item->key] = $item->value;
        }

        Config::set('meta', $meta);
    }

    /**
     * Loads all functions into the global scope
     *
     * @return void
     * @throws \ErrorException
     * @throws \OverflowException
     */
    public static function functions()
    {
        if ( ! is_admin()) {
            $fi = new FilesystemIterator(APP . 'functions', FilesystemIterator::SKIP_DOTS);

            foreach ($fi as $file) {
                $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

                if ($file->isFile() and $file->isReadable() and '.' . $ext == EXT) {
                    /** @noinspection PhpIncludeInspection */
                    require $file->getPathname();
                }
            }

            // include theme functions
            if (is_readable($path = PATH . 'themes' . DS . Config::meta('theme') . DS . 'functions.php')) {
                /** @noinspection PhpIncludeInspection */
                require $path;
            }
        }
    }

    /**
     * Applies any migrations missing in the current scheme
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function migrations()
    {
        $current    = Config::meta('current_migration');
        $migrate_to = Config::migrations('current');
        $migrations = new Migrations($current);
        $table      = Base::table('meta');

        if (is_null($current)) {
            $number = $migrations->up($migrate_to);

            Query::table($table)->insert([
                'key'   => 'current_migration',
                'value' => $number
            ]);
        } elseif ($current < $migrate_to) {
            $number = $migrations->up($migrate_to);

            Query::table($table)
                 ->where('key', '=', 'current_migration')
                 ->update(['value' => $number]);
        } elseif ($current > $migrate_to) {
            $number = $migrations->down($migrate_to);

            Query::table($table)
                 ->where('key', '=', 'current_migration')
                 ->update(['value' => $number]);
        }
    }

    /**
     * Registers all special pages, categories and menu items
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     * @throws \OverflowException
     */
    public static function register()
    {
        // register home page
        Registry::set('home_page', Page::home());

        // register posts page
        Registry::set('posts_page', Page::posts());

        if ( ! is_admin()) {
            $categories = [];

            // register categories
            foreach (Category::get() as $itm) {
                $categories[$itm->id] = $itm;
            }

            Registry::set('all_categories', $categories);

            // register menu items
            $pages = Page::where('status', '=', 'published')
                         ->where('show_in_menu', '=', '1')
                         ->sort('menu_order')
                         ->get();

            $pages = new Items($pages);

            Registry::set('menu', $pages);
            Registry::set('total_menu_items', $pages->length());
        }
    }
}
