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

}
