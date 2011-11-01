<?php
//  Stop direct access to the file. That's naughty.
    if(!$direct) die('No direct access allowed.');
    
//  Grab them config files
    include_once PATH . 'config.php';

//  Check the config's legit
    $req = array('database', 'metadata', 'debug');
    $missing = '';
    foreach($req as $key) {
        if(!array_key_exists($key, $config)) $missing .= $key . ' ';
    }
    
//  Override the var_dump function.
    function dump($str) {
        echo '<pre>';
            var_dump($str);
        echo '</pre>';
    }

//  If there aren't any missing variables, go ahead and load the CMS
    if($missing == '') {
    //  By default, we'll need the database and templating classes.
        include_once PATH . 'system/classes/database.php';
        include_once PATH . 'system/classes/template.php';
        
        $template = new Template($config);
        $template->run();
    } else {
        die('You are missing the following variables in your configuration: ' . $missing);
    }