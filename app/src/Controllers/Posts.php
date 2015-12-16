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

		$this->services->posts->hydrate([$article]);

		// page or post not found
		if( ! $page || ! $article) {
			return $this->notFound();
		}

		$content = new \ContentIterator([$article]);

		return $this->displayContent($page, $content, 'layout', ['article', 'index']);
	}

}
