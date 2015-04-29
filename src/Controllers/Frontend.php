<?php

namespace Controllers;

use Container;

abstract class Frontend extends ThemeAware {

	public function __construct(Container $app) {
		$this->setContainer($app);
		$this->setTheme($this->meta->key('theme', 'sail'));
	}

	public function notFound() {
		http_response_code(404);

		$page = new \StdClass;
		$page->title = 'Not Found';
		$page->html = 'The resource your looking for is not found.';

		$content = new \Content;
		$content->attach($page);

		return $this->displayContent($page, $content, 'layout', ['404', 'page', 'index']);
	}

	public function redirect($uri) {
		header('Location: '.$uri, true, 302);
	}

}
