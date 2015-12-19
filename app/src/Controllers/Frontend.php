<?php

namespace Controllers;

use Pimple\Container;
use Controllers\AbstractController;

abstract class Frontend extends AbstractController {

	public function __construct(Container $app) {
		$this->setContainer($app);
		$this->theme->setTheme($this->meta->key('theme', 'default'));
	}

	public function notFound() {
		$page = new \Models\Page([
			'title' => 'Not Found',
			'html' => 'The resource you’re looking for doesn’t exist!'
		]);

		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$vars['content'] = new \ContentIterator([$page]);
		$vars['page'] = $page;

		$template = $this->theme->render(['404', 'page', 'index'], $vars);

		$body = new \Http\Stream;
		$body->write($template);

		return $this->response->withStatus(404, 'Not Found')->withBody($body);
	}

}
