<?php


    function testConnection($host = '', $user = '', $pass = '', $db = '') {
        $host = (!empty($host) ? $host : 'localhost');
        $user = (!empty($user) ? $user : 'default'); 
        $pass = (!empty($pass) ? $pass : 'password');
        $db = (!empty($db) ? $db : 'anchor');
        
        return mysql_connect($host, $user, $pass) && mysql_select_db($db);
    }
    
    function installSQL($host, $user, $pass, $db) {
        $link = testConnection($host, $user, $pass);
        
        if($link && mysql_select_db($db, $link)) {
            foreach(explode(';', file_get_contents('../files/anchor.sql')) as $query) {
                if(!mysql_query($query, $link)) {
                    echo '<p>Unable to run query <em>"' . $query . '"</em>.</p>';
                }
            }
        } else {
            echo '<p class="error">Unable to connect to database with those details.</p>';
        }
    }
    
    $g = $_GET;
    
    if(isset($g['ajax'])) {
        echo !testConnection($g['host'], $g['user'], $g['pass'], $g['db']) ? 'bad' : 'good';
    }