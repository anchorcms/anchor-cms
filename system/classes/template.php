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
        $this->config['base_path'] = str_ireplace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $this->config['theme_path'] = $this->get('base_path') . 'theme/' . $this->get('theme');
        
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
    public function import($url, $bool = false) {
        if(file_exists($url)) {
            $include = include_once $url;
        }
        
        return ($bool == true ? !!$include : $this);
    }
    
    /**
     *    Determine if a file exists. If not, load the index file.
     */
    private function _include($theme, $fallback = '') {
        $import = $this->import(PATH . 'theme/' . $this->get('theme') . '/' . $theme . '.php', true);
        
        if(!$import) {
            return $this->import(PATH . 'theme/' . $this->get('theme') . '/' . ($fallback != '' ? $fallback : 'index') . '.php', true);
        } else {
            return $import;
        }
    }
    
    /**
     *    Run the actual templates
     */
    public function run() {
        $this->db = new Database($this->config['database']);
        
        //  Get the header (if it exists)
        $this->_include('includes/header');
        
        //  Work out which body file to fetch
        if($this->url[0] == 'home') {
            $this->_include('index');
        } else {
            if(!$this->db->fetch('slug', 'pages', array('slug' => $this->url[0]))) {
                $this->_include('404');
            } else {
                $this->_include($this->url[0], 'sub');
            }
        }

        //  And the footer
        $this->_include('includes/footer');
        
        return $this;
    }
    
    /**
     *    Theming functions
     */
    public function get($param) {
    
        //  Make sure it isn't trying to get a subparameter
        if(strpos($param, '/')) {
            $param = explode('/', $param);
            
            return $this->config[$param[0]][$param[1]];
        }
    
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
    
    public function getSlug() {
        //  Should return "posts", "home" or something like that
        return $this->url[0];
    }
    
    public function isHome() {
        return $this->getSlug() == 'home';
    }
}