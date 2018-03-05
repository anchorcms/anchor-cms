<?php

use System\database as DB;
use System\database\query;
use System\session;

/**
 * installer class
 * Installs AnchorCMS
 */
class installer
{
    /**
     * Database connection
     *
     * @var \System\database\connector
     */
    public static $connection;

    /**
     * Runs the installer
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function run()
    {
        // session data
        $settings = Session::get('install');

        // create database connection
        static::connect($settings);

        // check we have not already installed
        if ( ! static::$connection
            ->instance()
            ->query(
                'SHOW DATABASES LIKE ' . static::$connection
                    ->instance()
                    ->quote($settings['database']['name']) . ';')
            ->fetchColumn()) {

            // create the database
            static::$connection->instance()->query(
                'CREATE DATABASE ' . substr(
                    static::$connection->instance()
                                       ->quote($settings['database']['name']),
                    1,
                    -1
                ) . ';'
            );
        }

        // use the database
        static::$connection
            ->instance()
            ->query(
                'USE ' . substr(static::$connection
                    ->instance()
                    ->quote($settings['database']['name']),
                    1, -1) . ';'
            );

        if ( ! static::$connection
            ->instance()
            ->query('SHOW TABLES;')->fetchColumn()
        ) {

            // database charset config
            static::charset($settings);

            // install tables
            static::schema($settings);

            // insert metadata
            static::metadata($settings);

            // create user account
            static::account($settings);
        }

        // write database config
        static::database($settings);

        // write application config
        static::application($settings);

        // write session config
        static::session($settings);

        // install htaccess file
        static::rewrite($settings);
    }

    /**
     * Connects to the database
     *
     * @param array $settings connection configuration data
     *
     * @return void
     * @throws \ErrorException
     */
    private static function connect($settings)
    {
        $database = $settings['database'];

        // we do not specify the database name as it may not exist
        $config = [
            'driver'   => $database['driver'],
            'hostname' => $database['host'],
            'port'     => $database['port'],
            'username' => $database['user'],
            'password' => $database['pass'],
            'charset'  => DB::DEFAULT_CHARSET,
        ];

        static::$connection = DB::factory($config);
    }

    /**
     * Setup the database charset
     *
     * @param array $settings
     *
     * @return void
     */
    private static function charset($settings)
    {
        // Setup the charset of the database.
        $charset_query = sprintf("ALTER DATABASE %s CHARACTER SET = %s COLLATE = %s;", $settings['database']['name'], DB::DEFAULT_CHARSET, $settings['database']['collation']);
        static::$connection->instance()->query($charset_query);
    }

    /**
     * Creates the database schema
     *
     * @param array $settings
     *
     * @return void
     */
    private static function schema($settings)
    {
        $database = $settings['database'];

        $sql = Braces::compile(APP . 'storage/anchor.sql', [
            'now'     => gmdate('Y-m-d H:i:s'),
            'charset' => DB::DEFAULT_CHARSET,
            'prefix'  => isset($database['prefix']) ? $database['prefix'] : '',
        ]);

        static::$connection->instance()->query($sql);
    }

    /**
     * Creates the meta data table
     *
     * @param array $settings
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    private static function metadata($settings)
    {
        $metadata = $settings['metadata'];
        $database = $settings['database'];

        $config = [
            'sitename'    => $metadata['site_name'],
            'description' => $metadata['site_description'],
            'theme'       => $metadata['theme']
        ];

        $query = Query::table($database['prefix'] . 'meta', static::$connection);

        foreach ($config as $key => $value) {

            $query->insert(['key' => $key, 'value' => $value]);
        }
    }

    /**
     * Creates the account table
     *
     * @param array $settings
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    private static function account($settings)
    {
        $account  = $settings['account'];
        $database = $settings['database'];

        $query = Query::table($database['prefix'] . 'users', static::$connection);

        $query->insert([
            'username'  => $account['username'],
            'password'  => Hash::make($account['password']),
            'email'     => $account['email'],
            'real_name' => 'Administrator',
            'bio'       => 'The bouse',
            'status'    => 'active',
            'role'      => 'administrator'
        ]);
    }

    /**
     * Creates the database configuration file
     *
     * @param array $settings
     *
     * @return void
     */
    private static function database($settings)
    {
        $database = $settings['database'];

        $distro = Braces::compile(APP . 'storage/database.distro.php', [
            'hostname' => $database['host'],
            'port'     => $database['port'],
            'username' => $database['user'],
            'password' => $database['pass'],
            'database' => $database['name'],
            'prefix'   => $database['prefix'],
            'driver'   => $database['driver'],
            'charset'  => DB::DEFAULT_CHARSET,
        ]);

        file_put_contents(PATH . 'anchor/config/db.php', $distro);
    }

    /**
     * Creates the application settings file
     *
     * @param array $settings
     *
     * @return void
     */
    private static function application($settings)
    {
        $distro = Braces::compile(APP . 'storage/application.distro.php', [
            'url'      => addslashes($settings['metadata']['site_path']),
            'index'    => (mod_rewrite() ? '' : 'index.php'),
            'key'      => noise(),
            'language' => $settings['i18n']['language'],
            'timezone' => $settings['i18n']['timezone']
        ]);

        file_put_contents(PATH . 'anchor/config/app.php', $distro);
    }

    /**
     * Creates the session settings file
     *
     * @param array $settings
     *
     * @return void
     */
    private static function session($settings)
    {
        $database = $settings['database'];

        $distro = Braces::compile(APP . 'storage/session.distro.php', [
            'table' => $database['prefix'] . 'sessions'
        ]);

        file_put_contents(PATH . 'anchor/config/session.php', $distro);
    }

    /**
     * Creates the .htaccess file
     *
     * @param array $settings
     *
     * @return void
     */
    private static function rewrite($settings)
    {
        if (mod_rewrite() or (is_apache() and $settings['metadata']['rewrite'])) {
            $htaccess = Braces::compile(APP . 'storage/htaccess.distro', [
                'base'  => $settings['metadata']['site_path'],
                'index' => 'index.php?/$1'
            ]);

            if (isset($htaccess) and is_writable($filepath = PATH)) {
                file_put_contents($filepath . '.htaccess', $htaccess);
            } else {
                Session::put('htaccess', $htaccess);
            }
        }
    }
}
