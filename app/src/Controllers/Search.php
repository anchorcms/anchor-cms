<?php

namespace Controllers;

class Search extends Frontend {

	public function getIndex($request) {
		$keywords = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

		// set globals
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$page = new \Models\Page(['title' => sprintf('Search "%s"', $keywords)]);
		$vars['page'] = $page;

		$posts = $this->posts->whereNested(function($where) use($keywords) {
				$where('title', 'like', '%'.$keywords.'%')->or('content', 'like', '%'.$keywords.'%');
			})
			->where('status', '=', 'published')
			->get();

		$this->services->posts->hydrate($posts);

		$content = new \ContentIterator($posts);
		$vars['content'] = $content;

		return $this->theme->render(['search', 'index'], $vars);
	}

}
