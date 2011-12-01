<?php

    $sql = explode(';', file_get_contents('files/anchor.sql'));
    
    echo '<pre>';
    foreach($sql as $query) {
    	if($query !== '') {
    		echo 'mysql_query("' . trim($query) . '");';
    		echo '<br>';
    	}
    }