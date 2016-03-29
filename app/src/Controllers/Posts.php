<?php

namespace Controllers;

class Posts extends Frontend {

	public function getIndex($request) {
		$page = $this->container['mappers.pages']->where('id', '=', $this->container['mappers.meta']->key('posts_page'))
			->where('status', '=', 'published')
			->fetch();

		$slug = $request->getAttribute('post');
		$decoded = rawurldecode($slug);

		$article = $this->container['mappers.posts']->where('slug', '=', $decoded)
			->where('status', '=', 'published')
			->fetch();

		// page or post not found
		if( ! $page || ! $article) {
			return $this->notFound();
		}

		// set globals
		$vars['page'] = $page;
		$vars['meta'] = $this->container['mappers.meta']->all();

		$pages = $this->container['mappers.pages']->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->container['mappers.categories']->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$this->container['services.posts']->hydrate([$article]);

		$content = new \ContentIterator([$article]);
		$vars['content'] = $content;

		return $this->container['theme']->render(['article', 'index'], $vars);
	}

}
