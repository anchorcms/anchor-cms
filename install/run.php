<?php

use System\arr;
use System\autoloader;
use System\config;
use System\cookie;
use System\uri;

/*
 * Set your applications current timezone
 */
/** @noinspection PhpUndefinedMethodInspection */
date_default_timezone_set(Config::app('timezone', 'UTC'));

/*
 * Define the application error reporting level based on your environment
 */
switch (constant('ENV')) {
    case 'dev':
        ini_set('display_errors', true);
        error_reporting(-1);
        break;

    default:
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory([
    APP . 'models',
    APP . 'libraries',
    PATH . 'anchor/libraries'
]);

/*
 * Set the current uri from get
 */
if ($route = Arr::get($_GET, 'route', '/')) {
    Uri::$current = trim($route, '/') ?: '/';
}

/*
 *   Helper functions
 */

/**
 * Retrieves all time zones
 *
 * @return array
 */
function timezones()
{
    $timezones = [];
    $now       = new DateTime('now', new DateTimeZone('UTC'));

    foreach (DateTimeZone::listIdentifiers() as $timezone) {
        $now->setTimezone(new DateTimeZone($timezone));

        $offset  = $now->getOffset();
        $hours   = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));

        //Create the label
        $label = 'GMT';

        if ($offset) {
            $label .= ($hours < 0 ? '' : '+') . $hours;
            $label .= $minutes ? '.' . $minutes : '&nbsp;&nbsp;';
        }

        $label       .= '&nbsp;' . $timezone;
        $timezones[] = ['offset' => $offset, 'timezone_id' => $timezone, 'label' => $label];
    }

    //Sort by offset, and then by timezone_id.
    usort($timezones, function ($a, $b) {
        return ($a['offset'] - $b['offset']) - strcmp($b['timezone_id'], $a['timezone_id']);
    });

    return $timezones;
}

/**
 * Retrieves the current timezone
 *
 * @return float|int
 */
function current_timezone()
{
    return Cookie::read('anchor-install-timezone', 0) * 3600;
}

/**
 * Retrieves all supported languages
 *
 * @return array list of supported languages
 */
function languages()
{
    $languages = [];

    $path = PATH . 'anchor/language';
    $if   = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

    foreach ($if as $file) {
        if ($file->isDir()) {
            $languages[] = $file->getBasename();
        }
    }

    return $languages;
}

/**
 * Retrieves a list of preferred languages
 * TODO: Correct the typo. It makes me twitch.
 *
 * @return array
 */
function prefered_languages()
{
    $preferences = ['en-GB'];

    if ($lang = Arr::get($_SERVER, 'HTTP_ACCEPT_LANGUAGE')) {
        $pattern = '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i';

        if (preg_match_all($pattern, $lang, $matches)) {
            $preferences = $matches[1];
        }
    }

    return array_map(function ($str) {
        return str_replace('-', '_', $str);
    }, $preferences);
}

/**
 * Checks whether the current web server is an Apache httpd
 *
 * @return bool
 */
function is_apache()
{
    return stripos(PHP_SAPI, 'apache') !== false;
}

/**
 * Checks whether PHP is running as a CGI daemon
 *
 * @return bool
 */
function is_cgi()
{
    return stripos(PHP_SAPI, 'cgi') !== false;
}

/**
 * Checks whether mod_rewrite is enabled
 *
 * @return bool
 */
function mod_rewrite()
{
    if (is_apache() and function_exists('apache_get_modules')) {
        return in_array('mod_rewrite', apache_get_modules());
    }

    return getenv('HTTP_MOD_REWRITE') ? true : false;
}

/*
 *   Pre install checks
 */
$GLOBALS['errors'] = [];

/**
 * Checks a precondition for the installation
 *
 * @param string   $message User facing HTML message
 * @param \Closure $action  action to execute for the check
 *
 * @return void
 */
function check($message, $action)
{
    if ( ! $action()) {
        $GLOBALS['errors'][] = $message;
    }
}

check(
    '<code>We were unable to run composer our selves. Please run "composer install" from the command line to install Anchor. If you do not have composer installed please see <a href="https://getcomposer.org/">https://getcomposer.org/</a></code>',
    function () {
        return file_exists(PATH . 'vendor');
    }
);

check(
    '<code>content</code> directory needs to be writable
	so we can upload your images and files.',
    function () {
        return is_writable(PATH . 'content');
    }
);

check(
    '<code>anchor/config</code> directory needs to be temporarily writable
	so we can create your application and database configuration files.',
    function () {
        return is_writable(PATH . 'anchor/config');
    }
);

check(
    'Anchor requires the php module <code>pdo_mysql</code> to be installed.',
    function () {
        return extension_loaded('PDO') and extension_loaded('pdo_mysql');
    }
);

check(
    'Anchor requires the php module <code>GD</code> to be installed.',
    function () {
        return extension_loaded('gd');
    }
);

// mb_strtolower() in anchor\helpers.php
check(
    'Anchor requires the php module <code>mbstring</code> to be installed.',
    function () {
        return extension_loaded('mbstring');
    }
);

if (count($GLOBALS['errors'])) {
    $vars['errors'] = $GLOBALS['errors'];

    echo Layout::create('halt', $vars)->render();

    exit(0);
}

/*
 * Import defined routes
 */
/** @noinspection PhpIncludeInspection */
require APP . 'routes' . EXT;
