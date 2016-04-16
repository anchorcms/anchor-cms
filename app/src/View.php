<?php

class View {

	protected $path;

	protected $ext;

	public function __construct($path, $ext = '.phtml') {
		$this->setPath($path);
		$this->setExtension($ext);
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function getPath() {
		return $this->path;
	}

	public function setExtension($ext) {
		$this->ext = $ext;
	}

	public function getExtension() {
		return $this->ext;
	}

	public function getTemplatePath($file) {
		return $this->path.'/'.$file.$this->ext;
	}

	public function templateExists($file) {
		$path = $this->getTemplatePath($file);

		return is_file($path);
	}

	public function render($template, array $vars = [], array $_files = []) {
		$_path = $this->getTemplatePath($template);

		if(false === is_file($_path)) {
			throw new \InvalidArgumentException(sprintf('Template file does not exists: %s', $_path));
		}

		ob_start();

		extract($vars, EXTR_SKIP);

		foreach($_files as $_file) require $_file;

		require $_path;

		return ob_get_clean();
	}

}
