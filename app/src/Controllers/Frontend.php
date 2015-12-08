<?php

namespace Controllers;

use Pimple\Container;

abstract class Frontend extends ThemeAware {

	public function __construct(Container $app) {
		$this->setContainer($app);
		$this->setTheme($this->meta->key('theme', 'sail'));
	}

	public function notFound() {
		$page = new \Models\Page([
			'title' => 'Not Found',
			'html' => 'The resource your looking for is not found.'
		]);

		$content = new \Content;
		$content->attach($page);

		$template = $this->displayContent($page, $content, 'layout', ['404', 'page', 'index']);

		$body = new \Http\Stream;
		$body->write($template);

		return $this->response->withStatus(404, 'Not Found')->withBody($body);
	}

	public function redirect($uri) {
		return $this->response->withHeader('location', $uri);
	}

}
