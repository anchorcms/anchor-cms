<?php

class Paginator {

	public $results = array();
	public $count = 0;
	public $page = 1;
	public $perpage = 10;

	public $first;
	public $last;
	public $next;
	public $prev;

	public function __construct($results, $count, $page, $perpage, $url) {
		$this->results = $results;
		$this->count = $count;
		$this->page = $page;
		$this->perpage = $perpage;
		$this->url = rtrim($url, '/');

      $this->first = __('global.first');
      $this->last = __('global.last');
      $this->next = __('global.next');
      $this->prev = __('global.previous');
   }

	public function prev_link($text = null, $default = '', $attrs = array()) {
		if(is_null($text)) $text = $this->next;

		$pages = ceil($this->count / $this->perpage);

		if($this->page < $pages) {
			$page = $this->page + 1;

			return $this->link($text, array_merge(
				array('href' => $this->url . '/' . $page), $attrs
			));
		}

		return $default;
	}

	public function next_link($text = null, $default = '', $attrs = array()) {
		if(is_null($text)) $text = $this->prev;

		if($this->page > 1) {
			$page = $this->page - 1;

			return $this->link($text, array_merge(
				array('href' => $this->url . '/' . $page), $attrs
			));
		}

		return $default;
	}

	public function links() {
		$html = '';

		$pages = ceil($this->count / $this->perpage);
		$range = 4;

		if($pages > 1) {

			if($this->page > 1) {
				$page = $this->page - 1;

				$html = '<a href="' . $this->url . '">' . $this->first . '</a>
					<a href="' . $this->url . '/' . $page . '">' . $this->prev . '</a>';
			}

			for($i = $this->page - $range; $i < $this->page + $range; $i++) {
				if($i < 0) continue;

				$page = $i + 1;

				if($page > $pages) break;

				if($page == $this->page) {
					$html .= ' <strong>' . $page . '</strong> ';
				}
				else {
					$html .= ' ' . $this->link($page, $this->url . '/' . $page) . ' ';
				}
			}

			if($this->page < $pages) {
				$page = $this->page + 1;

				$html .= $this->link($this->next, $this->url . '/' . $page)
					  .  $this->link($this->last, $this->url . '/' . $pages);
			}

		}

		return $html;
	}

	public function link($text, $attrs = array()) {
		$attr = '';

		if(is_string($attrs)) {
			$attr = 'href="' . $attrs . '"';
		} else {
			foreach($attrs as $key => $val) {
				$attr .= $key . '="' . $val . '" ';
			}
		}

		return '<a ' . trim($attr) . '>' . $text . '</a>';
	}

}
