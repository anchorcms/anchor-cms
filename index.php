<?php

/*
               /   \
              |  o  |
               \   /
        ________) (________
       |                   |
       '------.     .------'
               |   |
               |   |
               |   |
               |   |
    /\         |   |         /\
   /_ \        /   \        / _\
     \ '.    .'     '.    .' /
      \  '--'         '--'  /
       '.                 .'
         '._           _.'
            `'-.   .-'`
                \ /
*/

define('DS', DIRECTORY_SEPARATOR);
define('ENV', getenv('APP_ENV') || 'dev');
define('VERSION', '0.12.1');
define('MIGRATION_NUMBER', 213);

define('PATH', __DIR__ . DS);
define('APP', PATH . 'anchor' . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require APP . 'composer_check' . EXT;
require SYS . 'start' . EXT;
