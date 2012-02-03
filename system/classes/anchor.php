<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This is the front controller that 
	will route all incoming requests
*/
class Anchor {

	public static function run() {
		// handle the requested uri
		$uri = static::parse();
		$segments = array();
		
		if(strlen($uri)) {
			$segments = explode('/', $uri);
		}

		// default to posts when no action is set
		$action = count($segments) ? array_shift($segments) : 'posts';
		
		// use the admin router
		$controller = ($action == 'admin') ? 'Routes_admin' : 'Routes';
		$reflector = new ReflectionClass($controller);
		
		// set the template path
		$theme = Config::get('theme');
		Template::path(PATH . 'themes/' . $theme . '/');
		
		// set template theme functions
		Template::theme_funcs(PATH . 'system/functions.php');
		
		// remove admin as an argument and set the default action
		// if there isnt one
		if($action == 'admin') {
			$default = Users::authed() === false ? 'login' : 'posts';
			$action = count($segments) ? array_shift($segments) : $default;
			
			// set template path for admin
			Template::path(PATH . 'system/admin/theme/');
			
			// set template theme functions for the admin
			Template::theme_funcs(PATH . 'system/admin/theme/functions.php');
		}
		
		// check we can find a action
		if($reflector->hasMethod($action) === false) {
			// method not found in controller
			return Response::error(404);
		}
		
		$reflector->getMethod($action)->invokeArgs(new $controller, $segments);
	}
	
	private static function parse() {
		// get uri
		$uri = Request::uri();

		// static routes
		$routes = array(
			'admin/(:any)/(:any)/(:num)' => 'admin/$1/$2/$3',
			'admin/(:any)/(:any)' => 'admin/$1/$2',
			'admin/(:any)' => 'admin/$1',
			'admin' => 'admin',
			
			'posts' => 'posts',
			
			'search/(:any)' => 'search/$1',
			'search' => 'search',
			
			'(:num)/(:num)/(:num)/(:any)' => 'article/$1/$2/$3/$4',
			'(:any)' => 'page/$1'
		);
		
		// define wild-cards
		$search = array(':any', ':num');
		$replace = array('[0-9a-zA-Z~%\.:_\\-]+', '[0-9]+');
		
		// parse routes
		foreach($routes as $route => $translated) {
			// replace wildcards
			$route = str_replace($search, $replace, $route);

			// look for matches
			if(preg_match('#' . $route . '#', $uri, $matches)) {
				// replace matched values
				foreach($matches as $k => $match) {
					$translated = str_replace('$' . $k, $match, $translated);
				}

				// return on first match
				return $translated;
			}
		}
		
		return $uri;
	}

}
