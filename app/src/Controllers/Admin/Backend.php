<?php

namespace Anchorcms\Controllers\Admin;

use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

abstract class Backend extends AbstractController {

	protected function renderTemplate(ResponseInterface $response, string $layout, string $template, array $vars = []) {
		$vars['sitename'] = $this->container['mappers.meta']->key('sitename');
		$vars['messages'] = $this->container['messages']->render();
		$vars['uri'] = $this->container['middleware.request']->getUri();
		$vars['body'] = $this->container['view']->render($template, $vars);

		$output = $this->container['view']->render($layout, $vars);

		$response->getBody()->write($output);
	}

}
