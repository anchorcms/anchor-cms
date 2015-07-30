<?php

namespace Controllers\Admin;

use Container;
use Controllers\Frontend;

abstract class Backend extends Frontend {

	protected $private = true;

	public function __construct(Container $app) {
		$this->setContainer($app);

		$this->setViewPath($this->paths['views']);
	}

	public function before() {
		if(true === $this->private && false === $this->session->has('user')) {
			$this->session->putFlash('messages', ['Please login to continue']);

			return $this->redirect('/admin/auth/login?forward='.$this->request->getUri()->getPath());
		}
	}

	protected function renderProfile() {
		$template = $this->getTemplate(['debug']);

		$view = new \View($template);

		return $view->render([
			'profile' => $this->query->getProfile(),
			'elapsed_time' => $this->benchmark,
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

}
