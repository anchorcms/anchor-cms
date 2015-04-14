<?php

namespace Controllers;

class Posts extends Frontend {

	public function index($pageSlug, $articleSlug) {
		$page = $this->pages->fetchBySlug($pageSlug);
		$article = $this->posts->where('slug', '=', $articleSlug)->where('status', '=', 'published')->fetch();

		// page not found
		// post not found
		// accessing post using a different page slug
		if(false === $page || false === $article || $page->id != $this->meta->key('posts_page')) {
			return $this->notFound();
		}

		$content = new \Content();
		$content->attach($article);

		return $this->displayContent($page, $content, 'layout', ['article', 'index']);
	}

}
