<?php

namespace Controllers\Admin;

use Container;
use Controllers\Frontend;

abstract class Backend extends Frontend {

	protected $private = true;

	public function __construct(Container $app) {
		$this->setContainer($app);

		$this->setViewPath($this->paths['views']);

		if(true === $this->private) {
			if(false === $this->session->has('user')) {
				$this->session->putFlash('messages', ['Please login to continue']);

				return $this->redirect('/admin/auth/login?forward='.$this->http->getUri()->getPath());
			}
		}

		if(false === $this->private) {
			if($this->session->has('user')) {
				return $this->redirect('/admin/posts');
			}
		}
	}

}
