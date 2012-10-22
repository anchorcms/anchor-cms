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

define('DS', '/');
define('ENV', (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'production'));
define('VERSION', '0.8');

define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'anchor' . DS);
define('SYS', PATH . 'system' . DS);

require SYS . 'bootstrap.php';