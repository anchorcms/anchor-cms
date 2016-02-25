<?php 

// If that didn't work then produce an error
if (!file_exists(PATH . 'vendor')) {
  die('<code>We were unable to run composer our selves. Please run "composer install" from the command line to install Anchor. If you do not have composer installed please see <a href="https://getcomposer.org/">https://getcomposer.org/</a></code>');
}
