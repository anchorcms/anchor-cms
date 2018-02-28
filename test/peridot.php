<?php

use Evenement\EventEmitterInterface;
use Peridot\Console\Environment;

error_reporting(-1);

return function (EventEmitterInterface $emitter) {
    $emitter->on('peridot.start', function (Environment $environment) {

        // set constants used in Anchor
        define('DS', DIRECTORY_SEPARATOR);
        define('ENV', getenv('APP_ENV'));
        define('PATH', __DIR__ . DS . '..' . DS);
        define('APP', PATH . 'anchor' . DS);
        define('SYS', PATH . 'system' . DS);
        define('EXT', '.php');

        // load the Anchor autoloader
        require_once(__DIR__ . '/../system/autoloader.php');

        // register the Anchor autoloader
        spl_autoload_register(['System\\Autoloader', 'load']);

        // set the base path to search
        System\Autoloader::directory(__DIR__ . '/..');

        // set the Peridot path to the unit test folder
        $environment->getDefinition()
                    ->getArgument('path')
                    ->setDefault('test/unit');
    });
};
