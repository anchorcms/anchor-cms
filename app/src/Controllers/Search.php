<?php

namespace Controllers;

class Search extends Frontend {

	public function getIndex($request) {
		$keywords = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

		$posts = $this->posts->whereNested(function($where) use($keywords) {
				$where('title', 'like', '%'.$keywords.'%')->or('content', 'like', '%'.$keywords.'%');
			})
			->where('status', '=', 'published')
			->get();

		$this->services->posts->hydrate($posts);

		$page = new \Models\Page(['title' => sprintf('Search "%s"', $keywords)]);
		$content = new \ContentIterator($posts);

		return $this->displayContent($page, $content, 'layout', ['search', 'index']);
	}

}
