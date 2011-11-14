<?php

class Theme {
    //  The theme's folder name, and the path to about.txt
    public $name,
           $attributes,
           
           //  Set a list of allowed theme metadata
           $allowed = array('theme_name', 'theme_page', 'description', 'author_name', 'author_site', 'license');
           
    public function __construct($name, $attributes = '') {
        //  Set the theme name
        $this->name = $name;
        
        //  Check the theme's attributes, if they're set.
        if($attributes != '') {
            $this->attributes = file_get_contents($attributes);
        }
    }
    
    public function parse($file) {
        //  Make sure we've got the attributes
        if(!$this->attributes) {
            $this->getAttributes();
        }
        
        //  BANG! Ha, I'm just kidding.
        //  But seriously, wear a hard hat when using explosives.
        $file = explode("\n", $this->attributes);
            
        $ret = array();
        
        //  Loop every newline as a test variable
        foreach($file as $line) {
            //  Explode the colon to work out what we're looking for
            $array = explode(':', $line, 2);
            
            //  And make it a nice, tidy array
            $ret[strtolower(str_replace(' ', '_', $array[0]))] = trim($array[1]);
        }
        
        return $ret;
    }
    
    public function getAttributes() {
        //  We should have the theme name. If not, something has gone terribly wrong.
        return file_get_contents(PATH . '/themes/' . $this->name . '/about.txt');
    }
}