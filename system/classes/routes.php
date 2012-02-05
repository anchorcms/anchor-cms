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
		if(($article = Posts::find(array('slug' => $slug))) === false) {
			return Response::error(404);
		}
		
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
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			if(isset($_POST['term']) and strlen($_POST['term'])) {
				return Response::redirect('search/' . rawurlencode($_POST['term']));
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
