<?php

namespace Controllers\Admin;

use Pimple\Container;
use Controllers\AbstractController;

abstract class Backend extends AbstractController {

	protected $private = true;

	public function __construct(Container $app) {
		$this->setContainer($app);
		$this->view->setPath($this->paths['views']);
	}

	public function before() {
		if(true === $this->private && false === $this->session->has('user')) {
			$this->session->putFlash('messages', ['Please login to continue']);

			return $this->redirect($this->url->to('/admin/auth/login?forward='.$this->request->getUri()->getPath()));
		}
	}

	protected function renderProfile() {
		return $this->view->render('debug', [
			'profile' => $this->query->getProfile(),
			'memory' => round(memory_get_usage() / 1024 / 1024, 2),
			'memory_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2),
		]);
	}

	public function after($response) {
		if($this->config->get('app.debug')) {
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
		$vars['messages'] = $this->messages->render();
		$vars['uri'] = $this->request->getUri();
		$vars['body'] = $this->view->render($template, $vars);

		return $this->view->render($layout, $vars);
	}

}
