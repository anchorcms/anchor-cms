<?php

namespace Anchorcms\Controllers;

use Pimple\Container;
use Controllers\AbstractController;

abstract class Frontend extends AbstractController {

	public function notFound() {
		$page = new \Models\Page([
			'title' => 'Not Found',
			'slug' => 'not-found',
			'html' => 'The resource you’re looking for doesn’t exist!'
		]);

		$vars['meta'] = $this->container['mappers.meta']->all();

		$pages = $this->container['mappers.pages']->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->container['mappers.categories']->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$vars['content'] = new \ContentIterator([$page]);
		$vars['page'] = $page;

		$body = $this->container['theme']->render(['404', 'page', 'index'], $vars);

		return $this->container['http.factory']->createResponse(404, [], $body);
	}

}
