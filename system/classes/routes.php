<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This class contains all routing details
*/
class Routes {

	public function posts() {
		if(($page = Pages::find(array('slug' => 'posts'))) === false) {
			return Response::error(404);
		}

		IoC::instance('page', $page, true);
		Template::render('posts');
	}
	
	public function article($year = 0, $month = 0, $day = 0, $slug = '') {
		$time = mktime(0, 0, 0, $month, $day, $year);
		$params = array('slug' => $slug, 'created' => date("Y-m-d", $time));
		
		if(($article = Posts::find($params)) === false) {
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
	
	public function search($term = '') {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
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
