<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

class Paginator {

	public $results = array();

	public $count = 0;

	public $page = 1;

	public $per_page = 10;

	public function __construct($results, $count, $page, $perpage, $url) {
		$this->results = $results;
		$this->count = $count;
		$this->page = $page;
		$this->perpage = $perpage;
		$this->url = rtrim($url, '/');
	}

	public function links() {
		$html = '';

		$pages = ceil($this->count / $this->perpage);
		$range = 4;

		if($pages > 1) {

			if($this->page > 1) {
				$page = $this->page - 1;

				$html = '<a href="' . $this->url . '">First</a> <a href="' . $this->url . '/' . $page . '">Previous</a>';
			}

			for($i = $this->page - $range; $i < $this->page + $range; $i++) {
				if($i < 0) continue;

				$page = $i + 1;

				if($page > $pages) break;

				if($page == $this->page) {
					$html .= ' <strong>' . $page . '</strong> ';
				}
				else {
					$html .= ' <a href="' . $this->url . '/' . $page . '">' . $page . '</a> ';
				}
			}

			if($this->page < $pages) {
				$page = $this->page + 1;

				$html .= '<a href="' . $this->url . '/' . $page . '">Next</a> <a href="' . $this->url . '/' . $pages . '">Last</a>';
			}

		}

		return $html;
	}

}