<?php

namespace Controllers;

class Page extends Frontend {

	public function getIndex($request) {
		$slug = $request->getAttribute('page');

		$page = $this->container['mappers.pages']->slug($slug);

		if(false === $page) {
			return $this->notFound();
		}

		// redirect homepage
		if($page->id == $this->container['mappers.meta']->key('home_page')) {
			return $this->redirect('/');
		}

		return $this->showPage($page);
	}

	public function getHome() {
		$page = $this->container['mappers.pages']->id($this->container['mappers.meta']->key('home_page'));

		if(false === $page) {
			return $this->notFound();
		}

		return $this->showPage($page);
	}

	public function getCategory($request) {
		$page = $this->container['mappers.pages']->id($this->container['mappers.meta']->key('posts_page'));

		$slug = $request->getAttribute('category');
		$category = $this->container['mappers.categories']->slug($slug);

		if(false === $category || false === $page) {
			return $this->notFound();
		}

		// set globals
		$vars['page'] = $page;
		$vars['category'] = $category;
		$vars['meta'] = $this->container['mappers.meta']->all();

		$pages = $this->container['mappers.pages']->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->container['mappers.categories']->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
		$perpage = $this->container['mappers.meta']->key('posts_per_page');

		$posts = $this->container['services.posts']->getPosts(['page' => $pagenum, 'perpage' => $perpage, 'category' => $category->id]);

		$content = new \ContentIterator($posts);
		$vars['content'] = $content;

		return $this->container['theme']->render(['category', 'posts', 'index'], $vars);
	}

	protected function showPage($page) {
		// name of template files to check for
		$names = [];

		// set globals
		$vars['page'] = $page;
		$vars['meta'] = $this->container['mappers.meta']->all();

		$pages = $this->container['mappers.pages']->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->container['mappers.categories']->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		// is posts page
		if($page->id == $this->container['mappers.meta']->key('posts_page')) {
			$pagenum = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT, ['options' => ['default' => 1]]);
			$perpage = $this->container['mappers.meta']->key('posts_per_page');

			$posts = $this->container['services.posts']->getPosts(['page' => $pagenum, 'perpage' => $perpage]);

			$vars['content'] = new \ContentIterator($posts);
			$names[] = 'posts';
		}
		else {
			$vars['content'] = new \ContentIterator([$page]);
			$names[] = 'page';
		}

		$names[] = 'index';

		return $this->container['theme']->render($names, $vars);
	}

}
