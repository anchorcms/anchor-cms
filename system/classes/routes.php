<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This class contains all routing details
*/
class Routes {

	public function posts() {
		if(($page = IoC::resolve('postspage')) === false) {
			return Response::error(404);
		}

		IoC::instance('page', $page, true);
		Template::render('posts');
	}
	
	public function article($slug = '') {
		// find article
		if(($article = Posts::find(array('slug' => $slug))) === false) {
			return Response::error(404);
		}
		
		// add comment
		if(Input::method() == 'POST') {
			if(Comments::add($article->id)) {
				$page = IoC::resolve('postspage');
				return Response::redirect($page->slug . '/' . $article->slug);
			}
		}
		
		// register single item for templating functions
		IoC::instance('article', $article, true);

		Template::render('article');
	}
	
	public function page($slug = '') {
		if(($page = Pages::find(array('slug' => $slug))) === false) {
			return Response::error(404);
		}

		IoC::instance('page', $page, true);
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
		
		$search = Posts::search($term, array('status' => 'published'));
		IoC::instance('search', $search, true);
		
		$page = new StdClass;
		$page->title = 'Search';
		IoC::instance('page', $page, true);
		Template::render('search');
	}

}
