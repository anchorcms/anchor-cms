<?php

use System\database as DB;
use System\input;
use System\route;
use System\session;

/**
 * Filters
 */
Route::action('check', function () {

    // Check if you have installed credit to Striker
    if (file_exists(APP . 'install.lock')) {
        return Layout::create('installed', [
            'installed' => true
        ]);
    }
});

/**
 * Start (Language Select)
 */
Route::get(['/', 'start'], [
    'before' => 'check',
    'main'   => function () {
        $vars['languages']          = languages();
        $vars['prefered_languages'] = prefered_languages();
        $vars['timezones']          = timezones();
        $vars['current_timezone']   = date_default_timezone_get();

        return Layout::create('start', $vars);
    }
]);

Route::post('start', [
    'before' => 'check',
    'main'   => function () {
        $i18n = Input::get([
            'language',
            'timezone'
        ]);

        $validator = new Validator($i18n);

        $validator->check('language')
                  ->is_max(2, 'Please select a language');

        $validator->check('timezone')
                  ->is_max(2, 'Please select a timezone');

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);

            return Response::redirect('start');
        }

        Session::put('install.i18n', $i18n);

        return Response::redirect('database');
    }
]);

/**
 * MySQL Database
 */
Route::get('database', [
    'before' => 'check',
    'main'   => function () {

        // check we have a selected language
        if ( ! Session::get('install.i18n')) {
            Notify::error('Please select a language');

            return Response::redirect('start');
        }

        $vars['drivers'] = [
            'mysql', 'sqlite',
        ];

        $vars['collations'] = [
            'utf8mb4_unicode_ci'    => 'Unicode (multilingual), case-insensitive',
            'utf8mb4_bin'           => 'Unicode (multilingual), Binary',
            'utf8mb4_czech_ci'      => 'Czech, case-insensitive',
            'utf8mb4_danish_ci'     => 'Danish, case-insensitive',
            'utf8mb4_esperanto_ci'  => 'Esperanto, case-insensitive',
            'utf8mb4_estonian_ci'   => 'Estonian, case-insensitive',
            'utf8mb4_general_ci'    => 'Unicode (multilingual), case-insensitive',
            'utf8mb4_hungarian_ci'  => 'Hungarian, case-insensitive',
            'utf8mb4_icelandic_ci'  => 'Icelandic, case-insensitive',
            'utf8mb4_latvian_ci'    => 'Latvian, case-insensitive',
            'utf8mb4_lithuanian_ci' => 'Lithuanian, case-insensitive',
            'utf8mb4_persian_ci'    => 'Persian, case-insensitive',
            'utf8mb4_polish_ci'     => 'Polish, case-insensitive',
            'utf8mb4_roman_ci'      => 'West European, case-insensitive',
            'utf8mb4_romanian_ci'   => 'Romanian, case-insensitive',
            'utf8mb4_slovak_ci'     => 'Slovak, case-insensitive',
            'utf8mb4_slovenian_ci'  => 'Slovenian, case-insensitive',
            'utf8mb4_spanish2_ci'   => 'Traditional Spanish, case-insensitive',
            'utf8mb4_spanish_ci'    => 'Spanish, case-insensitive',
            'utf8mb4_swedish_ci'    => 'Swedish, case-insensitive',
            'utf8mb4_turkish_ci'    => 'Turkish, case-insensitive',
        ];

        return Layout::create('database', $vars);
    }
]);

Route::post('database', [
    'before' => 'check',
    'main'   => function () {
        $database = Input::get([
            'host',
            'port',
            'user',
            'pass',
            'name',
            'collation',
            'prefix',
            'driver',
        ]);

        // Escape the password input
        $database['pass'] = addslashes($database['pass']);

        // test connection
        try {
            DB::factory([
                'driver'   => $database['driver'],
                'hostname' => $database['host'],
                'port'     => $database['port'],
                'username' => $database['user'],
                'password' => $database['pass'],
                'charset'  => DB::DEFAULT_CHARSET,
                'prefix'   => $database['prefix']
            ]);
        } catch (Exception $e) {
            Input::flash();
            Notify::error($e->getMessage());

            return Response::redirect('database');
        }

        Session::put('install.database', $database);

        return Response::redirect('metadata');
    }
]);

/**
 * Metadata
 */
Route::get('metadata', [
    'before' => 'check',
    'main'   => function () {
        // check we have a database
        if ( ! Session::get('install.database')) {
            Notify::error('Please enter your database details');

            return Response::redirect('database');
        }

        // windows users may return a \ so we replace it with a /
        $vars['site_path'] = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));
        $vars['themes']    = Themes::all();

        return Layout::create('metadata', $vars);
    }
]);

Route::post('metadata', [
    'before' => 'check',
    'main'   => function () {
        $metadata = Input::get(['site_name', 'site_description', 'site_path', 'theme', 'rewrite']);

        $validator = new Validator($metadata);

        $validator->check('site_name')
                  ->is_max(4, 'Please enter a site name (Minimum 4 characters)');

        $validator->check('site_description')
                  ->is_max(4, 'Please enter a site description (Minimum 4 characters)');

        $validator->check('site_path')
                  ->is_max(1, 'Please enter a site path');

        $validator->check('theme')
                  ->is_max(1, 'Please select a site theme');

        if ($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('metadata');
        }

        Session::put('install.metadata', $metadata);

        return Response::redirect('account');
    }
]);

/**
 * Account
 */
Route::get('account', [
    'before' => 'check',
    'main'   => function () {
        // check we have a database
        if ( ! Session::get('install.metadata')) {
            Notify::error('Please enter your site details');

            return Response::redirect('metadata');
        }

        return Layout::create('account', []);
    }
]);

Route::post('account', [
    'before' => 'check',
    'main'   => function () {
        $account = Input::get(['username', 'email', 'password']);

        $validator = new Validator($account);

        $validator->check('username')
                  ->is_max(3, 'Please enter a username (Minimum 3 characters)');

        $validator->check('email')
                  ->is_email('Please enter a valid email address');

        $validator->check('password')
                  ->is_max(6, 'Please enter a password (Minimum 6 characters)');

        if ($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('account');
        }

        Session::put('install.account', $account);

        // run install process
        try {
            Installer::run();
        } catch (Exception $e) {
            Input::flash();

            Notify::error($e->getMessage());

            return Response::redirect('account');
        }

        return Response::redirect('complete');
    }
]);

/**
 * Complete
 */
Route::get('complete', function () {

    // check we have a database
    if ( ! Session::get('install')) {
        Notify::error('Please select your language');

        return Response::redirect('start');
    }

    $settings          = Session::get('install');
    $vars['site_uri']  = $settings['metadata']['site_path'];
    $vars['admin_uri'] = rtrim($settings['metadata']['site_path'], '/') . '/index.php/admin/login';
    $vars['htaccess']  = Session::get('htaccess');

    // scrub session now we are done
    Session::erase('install');

    file_put_contents(APP . 'install.lock', time());

    return Layout::create('complete', $vars);
});

/*
 * 404 catch all
 */
Route::any(':all', function () {
    return Response::error(404);
});
