<?php

namespace Controllers;

use Pimple\Container;

abstract class Frontend {

	use ContainerTrait, ThemeTrait;

	public function __construct(Container $app) {
		$this->setContainer($app);
		$this->setTheme($this->meta->key('theme', 'default'));
	}

	public function notFound() {
		$page = new \Models\Page([
			'title' => 'Not Found',
			'html' => 'The resource you’re looking for doesn’t exist!'
		]);

		$content = new \ContentIterator([$page]);

		$template = $this->displayContent($page, $content, 'layout', ['404', 'page', 'index']);

		$body = new \Http\Stream;
		$body->write($template);

		return $this->response->withStatus(404, 'Not Found')->withBody($body);
	}

	public function redirect($uri) {
		return $this->response->withHeader('location', $uri);
	}

}
