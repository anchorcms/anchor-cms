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
        
        //  Fallbacks
        $u = $this->url[0];
        //  If there isn't a folder request (ie: /home), then set it
        //  If we're on the homepage of the posts index
        if(($u == 'posts' && !isset($this->url[1])) || $u == '') $this->_alias('home');
        //  Alias articles to posts
        if($u == 'articles') $this->_alias('posts');
        
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
        $include = '';
        
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
    
        //  Clean search URLs
        //  Check if there's any search POST data
        if(isset($_POST['term'])) {
            header('location: ' . $this->get('base_path') . 'search/' . urlencode($_POST['term']));
        }
    
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
    
    private function _alias($url) {
        return $this->url[0] = $url;
    }
    
    /**
     *    Theming functions
     */
    public function get($param) {
    
        //  Firstly, make sure they aren't trying to call the wrong method.
        if($param == 'title') return $this->$param();
        if($param == 'posts' || $param == 'slug') {
            $method = 'get' . ucwords($param);
            return $this->$method();
        }
    
        //  Make sure it isn't trying to get a subparameter
        if(strpos($param, '/')) {
            $param = explode('/', $param);
            
            return $this->config[$param[0]][$param[1]];
        }
    
        return (isset($this->config[$param]) ? $this->config[$param] : false);
    }
    
    //  Get the page's title
    public function title() {
        $title = $this->getContent($this->getSlug(), true);
        
        if(isset($title[0])) {
            return $title[0]->title;
        }
        
        return $this->config['metadata']['sitename'];
    }
    
    //  Return all of the posts in an object-array
    public function getPosts() {
        $array = array();
        
        if($this->url[0] == 'posts') {
            $array = array('published' => 1, 'slug' => $this->url[1]);
        } else {
            $array = array('published' => 1); 
        }
        
        return $this->db->fetch('', 'posts', $array);
    }
    
    //  Get the current URL slug
    public function getSlug() {
        //  Should return "posts", "home" or something like that
        return ($this->url[0] == 'home' ? 'posts' : $this->url[0]);
    }
    
    //  Get all of the pages
    //  @param: Show all the pages (even hidden ones)? Boolean.
    public function getPages($all = false) {
        //  Show only the visible pgaes
        return $this->db->fetch('', 'pages', array('visible' => (int) !$all));
    }
    
    //  Get the content from a single page.
    public function getContent($slug = '', $all = false) {
        $array = array('visible' => (int) !$all);
        
        if($slug != '') {
            $array['slug'] = $slug; 
        } else {
            $array['slug'] = $this->getSlug();
        }
        
        return $this->db->fetch('', 'pages', $array);
    }
    
    //  Check if this page is currently the homepage. Boolean.
    public function isHome() {
        //  getSlug will return "posts" on a homepage
        return $this->url[0] == 'home';
    }
    
    //  Check if the post has custom styles or not.
    public function isCustom() {
        //  Loop through the current posts
        foreach($this->getPosts() as $post) {
            $match = array($post->css, $post->js);
            
            return in_array($match, $post);
        }
        
        return false;
    }
    
    public function shorten($text, $length) {
        $len = strlen($text);
        
        if($len <= $length) {
            return $text;
        } else {
            return substr($text, 0, $length) . '&hellip;';
        }
    }
}