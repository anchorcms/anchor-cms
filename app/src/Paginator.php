<?php

class Paginator {

	protected $page;

	protected $pages;

	protected $url;

	public function __construct($page, $total, $perpage, $url) {
		$this->page = max(1, $page);
		$this->pages = ceil($total / $perpage);
		$this->url = $url;
	}

	protected function html($page, $text) {
		return '<a href="' . $this->url . $page . '">' . $text . '</a>';
	}

	public function links() {
		$links = [];
		$range = 4;

		if($this->pages == 1) {
			return '';
		}

		if($this->page > 1) {
			$links[] = $this->html($this->page - 1, 'Previous');
		}

		for($i = $this->page - $range; $i < $this->page + $range; $i++) {
			if($i < 0) continue;

			$page = $i + 1;

			if($page > $this->pages) break;

			if($page == $this->page) {
				$links[] = '<strong>' . $page . '</strong>';
			}
			else {
				$links[] = $this->html($page, $page);
			}
		}

		if($this->page < $this->pages) {
			$links[] = $this->html($this->page + 1, 'Next');
		}

		return implode(' ', $links);
	}

}
