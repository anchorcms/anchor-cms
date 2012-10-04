<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for metadata
*/
function site_name() {
	return Config::get('metadata.sitename');
}

function site_description() {
	return Config::get('metadata.description');
}

/*
	Twitter
*/
function twitter_account() {
	$twitter = Config::get('metadata.twitter');
	
	if(substr($twitter, 0, 1) == '@') {
	    $twitter = substr($twitter, 1);
	}
	
	return $twitter;
}

function twitter_url() {
    return 'http://twitter.com/' . twitter_account();
}


/*
    Page class
    <body class="<?php echo page_class(); ?>">
*/
function page_class() {
    $class = array();
    
    //  Add page slug
    if(page_slug(false)) {
        $class[] = 'page';
        $class[] = 'page-' . page_slug();
    }
    
    //  Add a custom CSS hook
    if(customised()) {
        $class[] = 'customised ';
    }
    
    //  Are we on an article
    if(article_slug(false)) {
        $class[] = 'article';
        $class[] = 'article-' . article_slug();
    }
    
    //  Add index/subpage
    $class[] = (is_homepage() ? 'home' : 'sub') . 'page';
    
    //  Add super-simple mobile detection
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    
    if($ua) {
        $ua = strtolower($ua);
        $checks = array('ipod', 'ipad', 'iphone', 'android', 'mobile');
        
        if(in_array($ua, $checks) !== false) {
            $class[] = 'mobile ';
        }
    }

    return trim(join(' ', $class));
}