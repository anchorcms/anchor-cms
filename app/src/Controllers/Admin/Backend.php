<?php

namespace Controllers\Admin;

use Pimple\Container;
use Controllers\AbstractController;

abstract class Backend extends AbstractController {

	protected $private = true;

	public function before($request) {
		$this->container['view']->setPath($this->container['paths']['views']);

		if(true === $this->private && false === $this->container['session']->has('user')) {
			$this->container['session']->putFlash('messages', ['Please login to continue']);

			return $this->redirect($this->container['url']->to('/admin/auth/login?forward='.$request->getUri()->getPath()));
		}
	}

	protected function renderProfile() {
		return $this->container['view']->render('debug', [
			'profile' => $this->container['query']->getProfile(),
			'memory' => round(memory_get_usage() / 1024 / 1024, 2),
			'memory_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2),
		]);
	}

	public function after($response) {
		if($this->container['config']->get('app.debug')) {
			$body = $response->getBody();

			if($body) {
				$profile = $this->renderProfile();

				$content = str_replace('</body>', $profile.'</body>', (string) $body);

				$stream = new \Http\Stream;
				$stream->write($content);
				$response->withBody($stream);
			}
		}
	}

	protected function renderTemplate($layout, $template, array $vars = []) {
		$vars['sitename'] = $this->container['mappers.meta']->key('sitename');
		$vars['messages'] = $this->container['messages']->render();
		$vars['uri'] = $this->container['middleware.request']->getUri();
		$vars['body'] = $this->container['view']->render($template, $vars);

		return $this->container['view']->render($layout, $vars);
	}

}
