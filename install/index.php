<?php

define('DS', '/');
define('VERSION', '0.8');

define('PATH', dirname(dirname(__FILE__)) . DS);
define('APP', PATH . 'install' . DS);
define('SYS', PATH . 'system' . DS);

require SYS . 'bootstrap.php';