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
		$page = $this->pages->id($this->meta->key('posts_page'));

		$slug = $request->getAttribute('category');
		$category = $this->categories->slug($slug);

		if(false == $category || false == $page) {
			return $this->notFound();
		}

		// set globals
		$vars['page'] = $page;
		$vars['category'] = $category;
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
		$perpage = $this->meta->key('posts_per_page');

		$posts = $this->services->posts->getPosts(['page' => $pagenum, 'perpage' => $perpage, 'category' => $category->id]);

		$content = new \ContentIterator($posts);
		$vars['content'] = $content;

		return $this->theme->render(['category', 'posts', 'index'], $vars);
	}

	protected function showPage($page) {
		// name of template files to check for
		$names = [];

		// set globals
		$vars['page'] = $page;
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		// is posts page
		if($page->id == $this->meta->key('posts_page')) {
			$pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
			$perpage = $this->meta->key('posts_per_page');

			$posts = $this->services->posts->getPosts(['page' => $pagenum, 'perpage' => $perpage]);

			$vars['content'] = new \ContentIterator($posts);
			$names[] = 'posts';
		}
		else {
			$vars['content'] = new \ContentIterator([$page]);
			$names[] = 'page';
		}

		$names[] = 'index';

		return $this->theme->render($names, $vars);
	}

}
