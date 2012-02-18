<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This class contains all routing details
*/
class Routes {

	public function article($slug = '') {
		// find article
		$params = array('slug' => $slug);

		// allow admin to view unpublished posts
		if(Users::authed() === false) {
			$params['status'] = 'published';
		}

		if(($article = Posts::find($params)) === false) {
			Log::warning('Article connot be found: ' . $slug);
			return Response::error(404);
		}
		
		// add comment
		if(Input::method() == 'POST') {
			if(Comments::add($article->id)) {
				$page = IoC::resolve('posts_page');
				return Response::redirect($page->slug . '/' . $article->slug);
			}
		}
		
		// register single item for templating functions
		IoC::instance('article', $article, true);

		Template::render('article');
	}
	
	public function page($slug = '') {
		// allow admin to view unpublished posts
		if(Users::authed() === false) {
			$params['status'] = 'published';
		}

		// if no slug is set we will use our default page
		if(empty($slug)) {
			$params['id'] = Config::get('metadata.home_page');
		} else {
			$params['slug'] = $slug;
		}

		// if we cant find either it looks like we're barney rubble (in trouble)
		if(($page = Pages::find($params)) === false) {
			Log::warning('Page connot be found: ' . $slug);
			return Response::error(404);
		}

		// store our page for template functions
		IoC::instance('page', $page, true);

		// does the current page host our posts?
		if($page->id == Config::get('metadata.posts_page')) {
			// render our posts template
			return Template::render('posts');
		}

		// render our page template
		Template::render('page');
	}
	
	public function rss() {
		// set headers
		Rss::headers();
		
		// set content
		Rss::generate();
	}
	
	public function search($term = '') {
		if(Input::method() == 'POST') {
			if(Input::post('term') !== false) {
				return Response::redirect('search/' . rawurlencode(Input::post('term')));
			}
		}
		
		$search = Posts::search($term, array(
			'status' => 'published', 
			'limit' => Config::get('metadata.posts_per_page', 10),
			'offset' => Input::get('offset', 0)
		));
		IoC::instance('search', $search, true);

		$total = Posts::search_count($term, array(
			'status' => 'published'
		));
		IoC::instance('total_search', $total, true);
		
		$page = new StdClass;
		$page->id = -1;
		$page->title = 'Search';
		IoC::instance('page', $page, true);
		Template::render('search');
	}

}
