<?php

//  Anchor's templating engine.
//  This is the big cheese. And no, I don't know what that means.

class Template {
    public $path,
           $config;
           
    /**
     *    Initiate the template class, call the setup() method.
     */
    public function __construct($config = '') {
        return $this->setup($config);
    }
    
    /**
     *    Set up our config variable
     */
    public function setup($config = '') {
        $this->config = $config;
        
        //  Set the URL from the request URL
        $this->url = array_slice(explode('/', URL), 1);
        if($this->url[0] == '') $this->url[0] = 'home';
        
        return $this;
    }
    
    public function __autoload($class) {
        $this->import(PATH . 'classes/' . strtolower($class) . '.php');
        return new $class;
    }
    
    /**
     *    Import a file
     */
    public function import($url) {
        if(file_exists($url)) {
            include_once $url;
        }
        
        return $this;
    }
    
    /**
     *    Run the actual templates
     */
    public function run() {
        $this->db = new Database($this->config['database']);
        $this->import(PATH . 'theme/' . $this->get('theme') . '/index.php');
    }
    
    /**
     *    Theming functions
     */
    public function get($param) {
        return $this->config[$param];
    }
    
    public function title() {
        return $this->config['metadata']['sitename'];
    }
}