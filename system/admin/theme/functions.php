<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	ADMIN Theme functions - happy templating!
*/

/**
	Posts
*/
function has_posts() {
	if(($posts = IoC::resolve('posts')) === false) {
		$params['sortby'] = 'id';
		$params['sortmode'] = 'desc';
		
		$posts = Posts::list_all($params);
		IoC::instance('posts', $posts, true);
	}
	
	return $posts->length() > 0;
}

function posts($params = array()) {
	if(has_posts() === false) {
		return false;
	}
	
	$posts = IoC::resolve('posts');

	if($result = $posts->valid()) {	
		// register single post
		IoC::instance('article', $posts->current(), true);
		
		// move to next
		$posts->next();
	}

	return $result;
}

/**
	Article
*/
function article_id() {
	if($itm = IoC::resolve('article')) {
		return $itm->id;
	}
	
	return '';
}

function article_title() {
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}
	
	return '';
}

function article_slug() {
	if($itm = IoC::resolve('article')) {
		return $itm->slug;
	}
	
	return '';
}

function article_description() {
	if($itm = IoC::resolve('article')) {
		return $itm->description;
	}
	
	return '';
}

function article_html() {
	if($itm = IoC::resolve('article')) {
		return $itm->html;
	}
	
	return '';
}

function article_css() {
	if($itm = IoC::resolve('article')) {
		return $itm->css;
	}
	
	return '';
}

function article_js() {
	if($itm = IoC::resolve('article')) {
		return $itm->js;
	}
	
	return '';
}

function article_created() {
	if($itm = IoC::resolve('article')) {
		return $itm->created;
	}
	
	return '';
}

function article_date() {
	if($itm = IoC::resolve('article')) {
		return date(COnfig::get('metadata.date_format'), $itm->created);
	}
	
	return '';
}

function article_author() {
	if($itm = IoC::resolve('article')) {
		return $itm->author;
	}
	
	return '';
}


function article_custom_fields() {
    if($itm = IoC::resolve('article')) {
    	if(isset($itm->custom_fields)) {
    	    // get associative array
    	    $data = json_decode($itm->custom_fields, true);
    	    return is_array($data) ? $data : array();
    	}
    }
    
    return array();
}

function article_custom_field($key, $default = '') {
    $fields = article_custom_fields();
    return isset($fields[$key]) ? $fields[$key] : $default;
}

function article_status() {
	if($itm = IoC::resolve('article')) {
		return $itm->status;
	}
	
	return '';
}

function article_comments() {
	if($itm = IoC::resolve('article')) {
		return $itm->comments ? true : false;
	}
	
	return false;
}

/**
	Comments
*/
function has_comments() {
	if(($itm = IoC::resolve('article')) === false) {
		return false;
	}
		
	if(($items = IoC::resolve('comments')) === false) {
		$items = Comments::list_all(array('post' => $itm->id));
		IoC::instance('comments', $items, true);
	}
	
	return $items->length() > 0;
}

function comments() {
	if(has_comments() === false) {
		return false;
	}

	$items = IoC::resolve('comments');

	if($result = $items->valid()) {	
		// register single comment
		IoC::instance('comment', $items->current(), true);
		
		// move to next
		$items->next();
	}

	return $result;
}

// single comments
function comment_id() {
	if($itm = IoC::resolve('comment')) {
		return $itm->id;
	}
	
	return '';
}

function comment_time() {
	if($itm = IoC::resolve('comment')) {
		return $itm->date;
	}
	
	return '';
}

function comment_date() {
	if($itm = IoC::resolve('comment')) {
		return date(Config::get('metadata.date_format'), $itm->date);
	}
	
	return '';
}

function comment_name() {
	if($itm = IoC::resolve('comment')) {
		return $itm->name;
	}
	
	return '';
}

function comment_text() {
	if($itm = IoC::resolve('comment')) {
		return $itm->text;
	}
	
	return '';
}

function comment_status() {
	if($itm = IoC::resolve('comment')) {
		return $itm->status;
	}
	
	return '';
}

/**
	Users
*/
function users($params = array()) {
	if(($users = IoC::resolve('users')) === false) {
		$users = Users::list_all($params);
		IoC::instance('users', $users, true);
	}

	if($result = $users->valid()) {	
		// register single post
		IoC::instance('user', $users->current(), true);
		
		// move to next
		$users->next();
	}

	return $result;
}

function user_id() {
	if($itm = IoC::resolve('user')) {
		return $itm->id;
	}
	
	return '';
}

function user_name() {
	if($itm = IoC::resolve('user')) {
		return $itm->username;
	}
	
	return '';
}

function user_email() {
	if($itm = IoC::resolve('user')) {
		return $itm->email;
	}
	
	return '';
}

function user_real_name() {
	if($itm = IoC::resolve('user')) {
		return $itm->real_name;
	}
	
	return '';
}

function user_bio() {
	if($itm = IoC::resolve('user')) {
		return $itm->bio;
	}
	
	return '';
}

function user_status() {
	if($itm = IoC::resolve('user')) {
		return $itm->status;
	}
	
	return '';
}

function user_role() {
	if($itm = IoC::resolve('user')) {
		return $itm->role;
	}
	
	return '';
}

/**
	Logged in user
*/
function user_authed() {
	return Users::authed() !== false;
}

function user_authed_id() {
	if(user_authed()) {
		return Users::authed()->id;
	}
	
	return '';
}

function user_authed_realname() {
	if(user_authed()) {
		return Users::authed()->real_name;
	}
	
	return '';
}

/**
	Pages
*/
function has_pages() {
	if(($pages = IoC::resolve('pages')) === false) {
		$pages = Pages::list_all();
		IoC::instance('pages', $pages, true);
	}
	
	return $pages->length() > 0;
}

function pages($params = array()) {
	if(has_pages() === false) {
		return false;
	}
	
	$pages = IoC::resolve('pages');

	if($result = $pages->valid()) {	
		// register single post
		IoC::instance('page', $pages->current(), true);
		
		// move to next
		$pages->next();
	}

	return $result;
}

function page_id() {
	if($itm = IoC::resolve('page')) {
		return $itm->id;
	}
	
	return '';
}

function page_slug() {
	if($itm = IoC::resolve('page')) {
		return $itm->slug;
	}
	
	return '';
}

function page_name() {
	if($itm = IoC::resolve('page')) {
		return $itm->name;
	}
	
	return '';
}

function page_title() {
	if($itm = IoC::resolve('page')) {
		return $itm->title;
	}
	
	return '';
}

function page_content() {
	if($itm = IoC::resolve('page')) {
		return $itm->content;
	}
	
	return '';
}

function page_status() {
	if($itm = IoC::resolve('page')) {
		return $itm->status;
	}
	
	return '';
}

/**
	notifications
*/
function notifications() {
	return Notifications::read();
}

/**
	Main menu
*/
function admin_menu() {
	$pages = array(
		'Posts' => 'admin/posts',
		'Pages' => 'admin/pages',
		'Users' => 'admin/users',
		'Metadata' => 'admin/metadata'
	);
	
	return $pages;
}

/**
	Meta data
*/
function site_name() {
	return Config::get('metadata.sitename');
}
function site_description() {
	return Config::get('metadata.description');
}
function twitter_account() {
	return Config::get('metadata.twitter');
}
function current_theme() {
	return Config::get('metadata.theme');
}

function replace($what) {
    return str_replace(PATH . 'themes/', '', $what);
}


/**
	Url helpers
*/
function theme_url($file = '') {
	return Config::get('application.base_url') . 'system/admin/theme/' . ltrim($file, '/');
}

function current_url() {
	return Url::current();
}

function menu_url() {
    return Request::uri();
}

function base_url($url = '') {
    return Url::make($url);
}

/**
	Pagination
*/
function pagination() {
	// @todo: generate pagaination for posts, pages and users
	return '';
}

/**
	String helpers
*/
function pluralise($amount, $str, $alt = '') {
    return $amount === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function truncate($str, $limit = 10, $elipse = ' [...]') {
	$words = preg_split('/\s+/', $str);

	if(count($words) <= $limit) {
		return $str;
	}

	return implode(' ', array_slice($words, 0, $limit)) . $elipse;
}
	
/**
    Error checking
*/
function latest_version() {
	// only run the version check once per session
	if(($version = Session::get('latest_version')) === false) {
		// returns plain text string with version number or 0 on failure.
		$version = floatval(Curl::get('http://anchorcms.com/version'));
		Session::set('latest_version', $version);
	}

	return $version;
}

function error_check() {
    $errors = array();

    //  Check the uploads folder is writable.
    if(is_writable(PATH . 'uploads') === false) {
        $errors[] = 'The <code>uploads</code> folder is not writable.';
    }
    
    //  Check for older versions
    if(version_compare(ANCHOR_VERSION, latest_version(), '<')) {
        $errors[] = 'Your version of Anchor is out of date. Please <a href="http://anchorcms.com">download the latest version</a>.';
    }
    
    //  And I can't think of anything else.
    if(1 !== 1) {
        $errors[] = 'PHP can&rsquo;t do math properly. The sky is falling. Get inside, quick!';
    }
    
    // outcome
    $result = (count($errors) ? $errors : false);
    
    // do something useful with it
    return $result;
}
