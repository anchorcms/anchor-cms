<?php

    $sql = explode(';', file_get_contents('files/anchor.sql'));
    
    foreach($sql as $query) {
    	if($query != '') echo 'mysql_query("' . trim($query) . '"); <br>';
    }