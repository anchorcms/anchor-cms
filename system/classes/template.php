<?php

//  Anchor's templating engine.
//  This is the big cheese. And no, I don't know what that means.

class Template {
    public $path,
           $config;
           
    private $_posts,
            $_content,
            $_pages;
           
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
        $this->url = explode('/', $this->get_url());
        
        //  Fallbacks
        $u = isset($this->url[0]) ? $this->url[0] : '';

        //  If there isn't a folder request (ie: /home), then set it
        //  If we're on the homepage of the posts index
        if(($u == 'posts' && !isset($this->url[1])) || $u == '') {
        	$this->_alias('posts');
        }
        
        //  Alias articles to posts
        if($u == 'articles') {
        	$this->_alias('posts');
        }
        
        return $this;
    }
    
    private function _alias($url) {
        return $this->url = array($url);
    }
    
    /**
     *    Get requested url
     */
    public function get_url() {
		if(isset($_SERVER['PATH_INFO'])) {
			$uri = $_SERVER['PATH_INFO'];
		}
		// try request uri
		elseif(isset($_SERVER['REQUEST_URI'])) {
			// make sure we can parse URI
			if(($uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === false) {
				throw new Exception('Malformed request URI');
			}
		}
		// cannot process request
		else {
			throw new Exception('Unable to determine the request URI');
		}
		
		return trim($uri, '/');
    }
    
    /**
     *    Import a file
     */
    public function import($url, $bool = false) {
        $include = '';
        
        if(file_exists($url)) {
            $include = include_once $url;
        }
        
        return ($bool === true ? !!$include : $this);
    }
    
    /**
     *    Determine if a file exists. If not, load the index file.
     */
    private function _include($theme, $fallback = '') {
        $import = $this->import(PATH . 'theme/' . $this->get('theme') . '/' . $theme . '.php', true);
        
        if(!$import) {
            if($fallback !== false) {
            	return $this->import(PATH . 'theme/' . $this->get('theme') . '/' . ($fallback != '' ? $fallback : 'index') . '.php', true);
            }
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
        
        if($this->url[0] === 'admin') {
            //  If it's the admin panel
            $admin = PATH . '/system/admin/';
            $url = isset($this->url[1]) ? $this->url[1] : 'index';
            
            $this->import($admin . 'actions/' . $url . '.php')
                 ->import($admin . 'includes/header.php')
                 ->import($admin . 'theme/' . $url . '.php')
                 ->import($admin . 'includes/footer.php');
            
            //  Don't run any other code
            exit;
        }
        
        //  Get the header (if it exists)
        $this->_include('includes/header', false);
        
        //  Work out which body file to fetch
        if(in_array($this->url[0], array('home', 'posts')) and empty($this->url[1])) {
            $this->_include('index');
        } else {
        	//  Check there's a page set in the database
            if(!$this->db->fetch('slug', 'pages', array('slug' => $this->url[0]))) {
                $this->_include('404');
            } else {
                $this->_include($this->url[0], 'sub');
            }
        }

        //  And the footer
        $this->_include('includes/footer', false);
        
        return $this;
    }
    
    /**
     *    Theming functions
     */
     
     
    /**
     *    Retrieve config files
     */
    public function get($param) {
    
        //  Firstly, make sure they aren't trying to call the wrong method.
        if($param == 'title') return $this->$param();
        
        //  Make a list of other duplicate methods
        $methods = array('posts', 'slug', 'url', 'pages', 'content');
        if(in_array($param, $methods)) {
            $method = 'get' . ucwords($param);
            
            //  Double-check the method exists, just so we don't break anything.
            return (method_exists($this, $method) ? $this->$method() : false);
        }
    
        //  Make sure it isn't trying to get a subparameter
        if(strpos($param, '/')) {
            $param = explode('/', $param);
            
            return $this->config[$param[0]][$param[1]];
        }
    
        return (isset($this->config[$param]) ? $this->config[$param] : false);
    }
    
    /**
     *    Get the page title
     */
    public function title($divider = '&middot;') {
        $title = $this->getContent($this->getSlug(), true);
        $return = '';
        
        if(isset($title[0])) {
            $return = $title[0]->title;
        }
        
        //  Get a post's title
        if(isset($this->url[1]) && $this->url[0] === 'posts') {
            $return = $this->getPosts();
            $return = $return[0]->title;
        }
        
        return $this->config['metadata']['sitename'] . ' ' . trim($divider) . ' ' . $return;
    }
    
    /**
     *    Get all of the posts
     *    @return Object
     */
    public function getPosts() {
        $array = array();
        
        if(!isset($this->_posts)) {
	        if($this->url[0] === 'posts' && isset($this->url[1])) {
	            $array = array('published' => 1, 'slug' => $this->url[1]);
	        } else {
	            $array = array('published' => 1); 
	        }
	        
	        foreach($this->db->fetch('', 'posts', $array) as $post) {
	        	$post->author = end($this->db->fetch('*', 'users', array('id' => $post->author)));
	        	$this->_posts[] = $post;
	        }
	    }
        
        return $this->_posts;
    }
    
    /**
     *    Return the current slug
     *    @return URL array's first parameter
     */
    public function getSlug() {
        //  Should return "posts", "home" or something like that
        return ($this->url[0] === 'home' ? 'posts' : $this->url[0]);
    }
    
    /**
     *    Get the entire URL string
     *    @return URL array
     */
	public function getURL() {
		return $this->url;
	}
    
    
    
    /**
     *    Get all of the pages
     *    @param: Show all the pages (even hidden ones)? Boolean.
     *    @return Object
     */
    public function getPages($all = false) {
        //  Show only the visible pgaes
        if(!isset($this->_pages)) {
            $this->_pages = $this->db->fetch('', 'pages', array('visible' => (int) !$all));
        }
        
        return $this->_pages;
    }
    
    /**
     *
     */
    public function getContent($slug = '', $all = false) {
        //  Set the filter array for the query
        $array = $all !== false ? array() : array('visible' => 1);
        $array['slug'] = ($slug !== '' ? $slug : $this->getSlug());
        
        if(!isset($this->_content[$array['slug']])) {
            $this->_content[$array['slug']] = $this->db->fetch('', 'pages', $array);
        }
        
        return $this->_content[$array['slug']];
    }
    
    //  Check if this page is currently the homepage. Boolean.
    public function isHome() {
        //  getSlug will return "posts" on a homepage
        return $this->url[0] === 'home';
    }
    
    //  Check if the post has custom styles or not.
    public function isCustom($post = '') {
        
        //  Set a fallback on our posts
        if($post === '') {
        	$post = $this->getPosts();
        	$post = isset($post[0]) ? $post[0] : $post;
        }
                
        //  Check that it's not a homepage, and there's some custom CSS or Javascript
        if(($this->getSlug() === 'posts' && isset($this->url[1])) && (!empty($post->css) || !empty($post->js))) {
        	return true;
        }
        
        return false;
    }
    
    //  And the matching "get" function
	public function getCustom() {
		//  Check we're using custom post design
		if($this->getSlug() == 'posts' && isset($this->_posts)) {
		
			//  Set our custom return values up
			$html = '';
			$url = array();
		
			//   Set the custom CSS
			if($this->_posts[0]->css) {
				$url['css'] = $this->_posts[0]->css;
				$html = '<link rel="stylesheet" href="' . $this->_posts[0]->css . '">';
			}
			
			//  And the Javascript
			if($this->_posts[0]->js) {
				$url['js'] = $this->_posts[0]->js;
				$html .= '<script src="' . $this->_posts[0]->js . '"></script>';
			}
		
		
			return (object) array(
				'url' => $url,
				'html' => $html
			);
		}
	}
	
	/**
	 *    Get an include part
	 */
	public function getInclude($part) {
	    return $this->_include('includes/' . $part, false);
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
