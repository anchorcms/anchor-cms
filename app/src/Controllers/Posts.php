<?php

namespace Controllers;

class Posts extends Frontend {

	public function getIndex($request) {
		$page = $this->pages->where('id', '=', $this->meta->key('posts_page'))
			->where('status', '=', 'published')
			->fetch();

		$article = $this->posts->where('slug', '=', $request->getAttribute('post'))
			->where('status', '=', 'published')
			->fetch();

		// page or post not found
		if( ! $page || ! $article) {
			return $this->notFound();
		}

		// set globals
		$vars['page'] = $page;
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$this->services->posts->hydrate([$article]);

		$content = new \ContentIterator([$article]);
		$vars['content'] = $content;

		return $this->theme->render(['article', 'index'], $vars);
	}

}
