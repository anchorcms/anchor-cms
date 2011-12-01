<?php
//  Stop direct access to the file. That's naughty.
    if(!$direct) die('No direct access allowed.');
    
//  Grab them config files
    include_once PATH . 'config.php';
    include_once PATH . 'system/messages.php';

//  Check the config's legit
    $req = array('database', 'metadata', 'debug');
    $missing = '';
    if($config) {
        foreach($req as $key) {
            if(!array_key_exists($key, $config)) $missing .= $key . ' ';
        }
    } else {
        $missing = 'all';
    }
    
//  Override the var_dump function.
    function dump($str) {
        echo '<pre>';
            var_dump($str);
        echo '</pre>';
    }
   
//  Autoload any classes
	function __autoload($class) {
	    $file = PATH . 'system/classes/' . strtolower($class) . '.php';
	    
	    if(file_exists($file)) {
	    	include_once $file;
	    	return new $class;
	    } else {
	    	echo $file;
	    }
	    
	    return false;
	}
	

//  If there aren't any missing variables, go ahead and load the CMS
    if($missing == '') {
        
        $template = new Template($config);
        $template->run();
    } else {
        //  Make sure they haven't deleted the folder.
        if(file_exists(PATH . 'install/index.php')) {
            //  Check the configuration files aren't COMPLETELY empty.
            if(empty($config)) {
                header('location: install/index.php');
            } else {
                echo $messages->missing_config;
            }
        } else {
            //  Seriously? How would you end up here?
            echo $messages->missing_installer;
            exit;
        }
    }
