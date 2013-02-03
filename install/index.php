<?php

define('DS', '/');
define('ENV', getenv('APP_ENV'));
define('VERSION', '0.8.2');

define('PATH', dirname(dirname(__FILE__)) . DS);
define('APP', PATH . 'install' . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require SYS . 'bootstrap' . EXT;