<?php

namespace Controllers\Admin;

use Pimple\Container;
use Controllers\Frontend;

abstract class Backend extends Frontend {

	protected $private = true;

	public function __construct(Container $app) {
		$this->setContainer($app);

		$this->view->setPath($this->paths['views']);
	}

	public function before() {
		if(true === $this->private && false === $this->session->has('user')) {
			$this->session->putFlash('messages', ['Please login to continue']);

			return $this->redirect('/admin/auth/login?forward='.$this->request->getUri()->getPath());
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

	protected function jsonResponse(array $data) {
		$stream = new \Http\Stream();
		$stream->write(json_encode($data));

		return $this->response->withHeader('content-type', 'application/json')->withBody($stream);
	}

}
