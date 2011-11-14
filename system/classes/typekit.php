<?php

/**
 *    I love me my custom fonts.
 *
 *    @author: visualidiot
 *    @project: Anchor CMS
 *    @date: 12/11/11 (that's the 12th November, if you don't know how dates work - I'm looking at you, America!)
 */

class Typekit {
    //  The Typekit API pattern (replace ? with ID)
    private $pattern = 'https://typekit.com/api/v1/json/kits/?/published';
    
    //  An array of font families
    public $fonts;
    
    /**
     *    @param $id: the kit ID
     */
    public function __construct($id = '') {
        $type = json_decode(file_get_contents(str_replace('?', $id, $this->pattern)));
        $this->fonts = $type->kit->families;
    }
    
    public function getFonts() {
        return $this->fonts;
    }
}

echo '<pre>';
$typekit = new Typekit('pfa5tzi');

var_dump($typekit->getFonts());