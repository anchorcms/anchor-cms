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

if(file_exists('install/index.php')) {
  die('Please click <a href="install/index.php">here</a> to begin the installation, or if you have already installed Anchor please delete the installation directory to continue.');
}

define('DS', DIRECTORY_SEPARATOR);
define('ENV', getenv('APP_ENV'));
define('VERSION', '0.9.2');

define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'anchor' . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require SYS . 'start' . EXT;