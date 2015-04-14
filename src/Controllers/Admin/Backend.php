<?php

namespace Controllers\Admin;

abstract class Backend extends \Controllers\Frontend {

	protected $private = true;

	public function __construct(\Container $app) {
		$this->container = $app;
		$paths = $this->config->get('paths');
		$this->templatePath = $paths['views'];
		$this->templateExt = '.phtml';

		if(true === $this->private) {
			if(false === $this->session->has('user')) {
				$this->session->putFlash('messages', ['Please login to continue']);

				return $this->redirect('/admin/auth/login?forward='.$this->http->getUri());
			}
		}

		if(false === $this->private) {
			if($this->session->has('user')) {
				return $this->redirect('/admin/posts');
			}
		}
	}

}
