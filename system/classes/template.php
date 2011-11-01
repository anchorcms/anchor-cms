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
        if($this->url[0] == 'posts' && !isset($this->url[1])) $this->url[0] = 'home';
        
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
        
        if($this->url[0] == 'home') {
            $this->import(PATH . 'theme/' . $this->get('theme') . '/index.php');
        } else {
            $this->import(PATH . 'theme/' . $this->get('theme') . '/' . $this->url[0] . '.php');        
        }
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
    
    public function getPosts() {
        $array = array();
        
        if($this->url[0] == 'posts') {
            $array = array('published' => 1, 'slug' => $this->url[1]);
        } else {
            $array = array('published' => 1); 
        }
        
        return $this->db->fetch('', 'posts', $array);
    }
}