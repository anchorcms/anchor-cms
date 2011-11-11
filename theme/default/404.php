This is a 404 page.

<pre>
<?php
    
    echo '<br><br>';

    //  Get the file
    $file = explode("\n", file_get_contents(PATH . 'theme/default/about.txt'));
    
    //  Make a return array available
    $ret = array();
    
    //  Loop every newline as a test variable
    foreach($file as $test) {
        //  Explode to array
        $array = explode(':', $test, 2);
        $ret[strtolower(str_replace(' ', '_', $array[0]))] = trim($array[1]);
    }
    
    var_dump($ret);
?>