<?php

namespace Anchorcms;

class View {

	protected $path;

	protected $ext;

	public function __construct(string $path, string $ext) {
		$this->setPath($path);
		$this->setExt($ext);
	}

	public function setPath(string $path) {
		$this->path = realpath($path);
	}

	public function setExt(string $ext) {
		$this->ext = $ext;
	}

	public function e(string $str) {
		return htmlentities($str, ENT_COMPAT | ENT_HTML5, 'UTF-8', false);
	}

	public function render(string $template, array $vars = []) {
		ob_start();

		extract($vars, EXTR_SKIP);

		require $this->path . '/' . $template . '.' . $this->ext;

		return ob_get_clean();
	}

}
