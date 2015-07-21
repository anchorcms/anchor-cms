<?php

namespace Controllers;

class Page extends Frontend {

	public function getIndex($request) {
		$slug = $request->getAttribute('page');

		$page = $this->pages->slug($slug);

		if(false === $page) {
			return $this->notFound();
		}

		// redirect homepage
		if($page->id == $this->meta->key('home_page')) {
			return $this->redirect('/');
		}

		return $this->showPage($page);
	}

	public function getHome() {
		$page = $this->pages->id($this->meta->key('home_page'));

		if(false === $page) {
			return $this->notFound();
		}

		return $this->showPage($page);
	}

	public function getCategory($request) {
		$slug = $request->getAttribute('category');

		$page = $this->pages->id($this->meta->key('posts_page'));
		$category = $this->categories->slug($slug);

		if(false == $category) {
			return $this->notFound();
		}

		$paging = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?: 1;
		$per_page = $this->meta->key('posts_per_page');
		$posts = $this->posts->published($per_page, $page)->where('category', '=', $category->id)->get();
		$content = new \Content($posts);

		return $this->displayContent($page, $content, 'layout', ['category', 'posts', 'index'], ['category' => $category]);
	}

	protected function showPage($page) {
		// name of template files to check for
		$names = [];

		// is posts page
		if($page->id == $this->meta->key('posts_page')) {
			$pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
			$perpage = $this->meta->key('posts_per_page');
			$offset = ($pagenum - 1) * $perpage;

			$posts = $this->posts->where('status', '=', 'published')->take($perpage)->skip($offset)->get();
			$content = new \Content($posts);

			$names[] = 'posts';
		}
		else {
			$content = new \Content([$page]);
			$names[] = 'page';
		}

		$names[] = 'index';

		return $this->displayContent($page, $content, 'layout', $names);
	}

}
