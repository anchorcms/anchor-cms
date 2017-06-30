<?php 

// If that didn't work then produce an error
if (!file_exists(PATH . 'vendor')) {
    $completeGud = 0;
    $out = array();
    $cmd = "composer install";
    exec("$cmd 2>&1", $out, $completeGud); // 2>&1 so STDERR also goes to STDOUT.
    if($completeGud !== 0) die('<code>We were unable to run composer our selves. Please run "composer install" from the command line to install Anchor. If you do not have composer installed please see <a href="https://getcomposer.org/">https://getcomposer.org/</a><br>(Error #' . $completeGud . ') Here is the output of the command:<br>' . implode("<br>", $out) . '</code>');
}
