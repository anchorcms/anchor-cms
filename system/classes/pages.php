<?php defined('IN_CMS') or die('No direct access allowed.');

class Pages {

	public static function list_all($params = array()) {
		$sql = "select * from pages where 1 = 1";
		$args = array();
		
		if(isset($params['status'])) {
			$sql .= " and status = ?";
			$args[] = $params['status'];
		}
		
		$uri = Request::uri();
		$pages = array();

		foreach(Db::results($sql, $args) as $page) {
			$page->url = '/' . $page->slug;
			$page->active = (strlen($uri) ? strpos($page->slug, Request::uri()) !== false : ($page->slug == 'posts' ? true : false));
			$pages[] = $page;
		}

		return $pages;
	}

	public static function find($slug) {
		$sql = "select * from pages where slug = ?";
		$args = array($slug);
		
		if(isset($params['status'])) {
			$sql .= " and status = ?";
			$args[] = $params['status'];
		}

		return Db::row($sql, $args);
	}

}
