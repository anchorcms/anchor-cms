<?php

namespace Anchorcms\Controllers;

class Search extends Frontend {

	public function getIndex($request) {
		$keywords = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

		// set globals
		$vars['meta'] = $this->container['mappers.meta']->all();
		$vars['keywords'] = $keywords;

		$pages = $this->container['mappers.pages']->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->container['mappers.categories']->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$page = new \Models\Page(['title' => sprintf('Search "%s"', $keywords)]);
		$vars['page'] = $page;

		$posts = $this->container['mappers.posts']->whereNested(function($where) use($keywords) {
				$where('title', 'like', '%'.$keywords.'%')->or('content', 'like', '%'.$keywords.'%');
			})
			->where('status', '=', 'published')
			->get();

		$this->container['services.posts']->hydrate($posts);

		$content = new \ContentIterator($posts);
		$vars['content'] = $content;

		return $this->container['theme']->render(['search', 'index'], $vars);
	}

}
